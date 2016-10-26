<?php
/**
 * Created by PhpStorm.
 * User: phuon
 * Date: 9/21/2016
 * Time: 3:51 PM
 */
namespace navatech\roxymce\helpers;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Url;

class FileHelper {

	/**
	 * Return filesize in human readable
	 *
	 * @param     $bytes
	 * @param int $decimals
	 *
	 * @return string
	 */
	public static function fileSize($bytes, $decimals = 2) {
		$size   = array(
			'B',
			'kB',
			'MB',
			'GB',
			'TB',
			'PB',
			'EB',
			'ZB',
			'YB',
		);
		$factor = (int) floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $size[$factor];
	}

	/**
	 * Return file icon by mime type
	 *
	 * @param      $file
	 * @param bool $is_big
	 *
	 * @return string
	 */
	public static function fileIcon($file, $is_big = false) {
		$extension = pathinfo($file, PATHINFO_EXTENSION);
		if ($is_big) {
			$icon = Yii::getAlias('@roxymce/web/images/filetypes/big/file_extension_' . $extension . '.png');
		} else {
			$icon = Yii::getAlias('@roxymce/web/images/filetypes/file_extension_' . $extension . '.png');
		}
		if (file_exists($icon)) {
			$data = file_get_contents($icon);
		} else {
			$data = file_get_contents(Yii::getAlias('@roxymce/web/images/filetypes/unknown.png'));
		}
		return 'data:image/png;base64,' . base64_encode($data);
	}

	/**
	 * Return true url of file
	 *
	 * @param $path
	 *
	 * @return mixed
	 */
	public static function fileUrl($path) {
		return str_replace('\\', '/', str_replace(Yii::$app->basePath, Url::base(true), $path));
	}

	/**
	 * Return true url of file for image file only
	 *
	 * @param $path
	 *
	 * @return mixed|string
	 */
	public static function filePreview($path) {
		$allowedTypes = array(
			IMAGETYPE_PNG,
			IMAGETYPE_JPEG,
			IMAGETYPE_GIF,
		);
		$detectedType = exif_imagetype($path);
		if (in_array($detectedType, $allowedTypes)) {
			return self::fileUrl($path);
		} else {
			return self::fileIcon($path, true);
		}
	}

	/**
	 * Returns file extension without dot
	 *
	 * @param string $filename
	 *
	 * @return string
	 */
	public static function fileExtension($filename) {
		$ext = '';
		if (mb_strrpos($filename, '.') !== false) {
			$ext = mb_substr($filename, mb_strrpos($filename, '.') + 1);
		}
		return strtolower($ext);
	}

	/**
	 * Returns MIME type of $filename
	 *
	 * @param string $filename
	 *
	 * @return string
	 */
	public static function mimeType($filename) {
		$ext = self::fileExtension($filename);
		switch (strtolower($ext)) {
			case 'jpg':
				$type = 'image/jpeg';
				break;
			case 'jpeg':
				$type = 'image/jpeg';
				break;
			case 'gif':
				$type = 'image/gif';
				break;
			case 'png':
				$type = 'image/png';
				break;
			case 'bmp':
				$type = 'image/bmp';
				break;
			case 'tiff':
				$type = 'image/tiff';
				break;
			case 'tif':
				$type = 'image/tiff';
				break;
			case 'pdf':
				$type = 'application/pdf';
				break;
			case 'rtf':
				$type = 'application/msword';
				break;
			case 'doc':
				$type = 'application/msword';
				break;
			case 'xls':
				$type = 'application/vnd.ms-excel';
				break;
			case 'zip':
				$type = 'application/zip';
				break;
			case 'swf':
				$type = 'application/x-shockwave-flash';
				break;
			default:
				$type = 'application/octet-stream';
		}
		return $type;
	}

	/**
	 * @param $fileName
	 *
	 * @return bool
	 * @throws InvalidConfigException
	 */
	public static function isImage($fileName) {
		$ret = false;
		$ext = strtolower(self::fileExtension($fileName));
		if ($ext === 'jpg' || $ext === 'jpeg' || $ext === 'jpe' || $ext === 'png' || $ext === 'gif' || $ext === 'ico') {
			$ret = true;
		}
		return $ret;
	}
}