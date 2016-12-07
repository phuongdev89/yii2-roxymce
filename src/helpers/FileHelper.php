<?php
/**
 * Created by Navatech.
 * @project roxymce
 * @author  Le Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    4:19 CH
 * @version 2.0.0
 */
namespace navatech\roxymce\helpers;

use navatech\roxymce\Module;
use Yii;
use yii\base\InvalidConfigException;

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
		/**@var Module $module */
		$module    = Yii::$app->getModule('roxymce');
		$uploadUrl = str_replace('\\', '/', Yii::getAlias($module->uploadFolder));
		$path      = Yii::getAlias(str_replace('\\', '/', $path));
		return str_replace('\\', '/', str_replace($uploadUrl, $module->uploadUrl, $path));
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

	/**
	 * @param        $convert
	 * @param string $char
	 *
	 * @return mixed|string
	 */
	public static function removeSign($convert, $char = "_") {
		$vietnameseChar  = "à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|ì|í|ị|ỉ|ĩ|ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|ỳ|ý|ỵ|ỷ|ỹ|đ|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|Ì|Í|Ị|Ỉ|Ĩ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|Ỳ|Ý|Ỵ|Ỷ|Ỹ|Đ";
		$unicodeChar     = "a|a|a|a|a|a|a|a|a|a|a|a|a|a|a|a|a|e|e|e|e|e|e|e|e|e|e|e|i|i|i|i|i|o|o|o|o|o|o|o|o|o|o|o|o|o|o|o|o|o|u|u|u|u|u|u|u|u|u|u|u|y|y|y|y|y|d|A|A|A|A|A|A|A|A|A|A|A|A|A|A|A|A|A|E|E|E|E|E|E|E|E|E|E|E|I|I|I|I|I|O|O|O|O|O|O|O|O|O|O|O|O|O|O|O|O|O|U|U|U|U|U|U|U|U|U|U|U|Y|Y|Y|Y|Y|D";
		$vietnameseChars = explode("|", $vietnameseChar);
		$unicodeChars    = explode("|", $unicodeChar);
		$str             = strtolower(str_replace($vietnameseChars, $unicodeChars, $convert));
		$str             = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
		$str             = preg_replace("/[\/_|+ -]+/", $char, $str);
		return $str;
	}
}