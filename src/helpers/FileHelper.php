<?php
/**
 * Created by PhpStorm.
 * User: phuon
 * Date: 9/21/2016
 * Time: 3:51 PM
 */
namespace navatech\roxymce\helpers;

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
	public static function filesize($bytes, $decimals = 2) {
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
	public static function fileicon($file, $is_big = false) {
		$extension = pathinfo($file, PATHINFO_EXTENSION);
		if ($is_big) {
			$icon = \Yii::getAlias('@roxymce/web/images/filetypes/big/file_extension_' . $extension . '.png');
		} else {
			$icon = \Yii::getAlias('@roxymce/web/images/filetypes/file_extension_' . $extension . '.png');
		}
		if (file_exists($icon)) {
			$data = file_get_contents($icon);
		} else {
			$data = file_get_contents(\Yii::getAlias('@roxymce/web/images/filetypes/unknown.png'));
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
	public static function fileurl($path) {
		return str_replace(\Yii::$app->basePath, Url::base(true), $path);
	}

	/**
	 * Return true url of file for image file only
	 *
	 * @param $path
	 *
	 * @return mixed|string
	 */
	public static function filepreview($path) {
		$allowedTypes = array(
			IMAGETYPE_PNG,
			IMAGETYPE_JPEG,
			IMAGETYPE_GIF,
		);
		$detectedType = exif_imagetype($path);
		if (in_array($detectedType, $allowedTypes)) {
			return self::fileurl($path);
		} else {
			return self::fileicon($path, true);
		}
	}
}