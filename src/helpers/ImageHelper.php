<?php
/**
 * Created by Navatech.
 * @project RoxyMce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    16/02/2016
 * @time    9:06 CH
 * @version 1.0.0
 */
namespace navatech\roxymce\helpers;

use yii\base\InvalidConfigException;

/**
 * ImageHelper help some functions on image
 */
class ImageHelper {

	/**
	 * @param $path
	 *
	 * @return null|resource
	 * @throws InvalidConfigException
	 */
	public static function getImage($path) {
		$img = null;
		switch (FileHelper::getExtension(basename($path))) {
			case 'png':
				$img = imagecreatefrompng($path);
				break;
			case 'gif':
				$img = imagecreatefromgif($path);
				break;
			default:
				$img = imagecreatefromjpeg($path);
		}
		return $img;
	}

	/**
	 * @param        $img
	 * @param        $type
	 * @param string $destination
	 * @param int    $quality
	 *
	 * @throws InvalidConfigException
	 */
	public static function outputImage($img, $type, $destination = '', $quality = 90) {
		if (is_string($img)) {
			$img = self::getImage($img);
		}
		switch (strtolower($type)) {
			case 'png':
				imagepng($img, $destination);
				break;
			case 'gif':
				imagegif($img, $destination);
				break;
			default:
				imagejpeg($img, $destination, $quality);
		}
	}

	/**
	 * @param     $source
	 * @param     $destination
	 * @param int $width
	 * @param int $height
	 * @param int $quality
	 *
	 * @throws InvalidConfigException
	 */
	public static function resize($source, $destination, $width = 150, $height = 0, $quality = 90) {
		$tmp = getimagesize($source);
		$w   = $tmp[0];
		$h   = $tmp[1];
		$r   = $w / $h;
		if ($w <= ($width + 1) && (($h <= ($height + 1)) || (!$height && !$width))) {
			if ($source !== $destination) {
				self::outputImage($source, FileHelper::getExtension(basename($source)), $destination, $quality);
			}
			return;
		}
		$newWidth  = $width;
		$newHeight = floor($newWidth / $r);
		if (($height > 0 && $newHeight > $height) || !$width) {
			$newHeight = $height;
			$newWidth  = (int) ($newHeight * $r);
		}
		$thumbImg = imagecreatetruecolor($newWidth, $newHeight);
		$img      = self::getImage($source);
		imagecopyresampled($thumbImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $w, $h);
		self::outputImage($thumbImg, FileHelper::getExtension(basename($source)), $destination, $quality);
	}

	/**
	 * @param     $source
	 * @param     $destination
	 * @param     $width
	 * @param     $height
	 * @param int $quality
	 *
	 * @throws InvalidConfigException
	 */
	public static function cropCenter($source, $destination, $width, $height, $quality = 90) {
		$tmp = getimagesize($source);
		$w   = $tmp[0];
		$h   = $tmp[1];
		if (($w <= $width) && (!$height || ($h <= $height))) {
			self::outputImage(self::getImage($source), FileHelper::getExtension(basename($source)), $destination, $quality);
		}
		$ratio      = $width / $height;
		$top        = $left = 0;
		$cropWidth  = floor($h * $ratio);
		$cropHeight = floor($cropWidth / $ratio);
		if ($cropWidth > $w) {
			$cropWidth  = $w;
			$cropHeight = $w / $ratio;
		}
		if ($cropHeight > $h) {
			$cropHeight = $h;
			$cropWidth  = $h * $ratio;
		}
		if ($cropWidth < $w) {
			$left = floor(($w - $cropWidth) / 2);
		}
		if ($cropHeight < $h) {
			$top = floor(($h - $cropHeight) / 2);
		}
		self::crop($source, $destination, $left, $top, $cropWidth, $cropHeight, $width, $height, $quality);
	}

	/**
	 * @param     $source
	 * @param     $destination
	 * @param     $x
	 * @param     $y
	 * @param     $cropWidth
	 * @param     $cropHeight
	 * @param     $width
	 * @param     $height
	 * @param int $quality
	 *
	 * @throws InvalidConfigException
	 */
	public static function crop($source, $destination, $x, $y, $cropWidth, $cropHeight, $width, $height, $quality = 90) {
		$thumbImg = imagecreatetruecolor($width, $height);
		$img      = self::getImage($source);
		imagecopyresampled($thumbImg, $img, 0, 0, $x, $y, $width, $height, $cropWidth, $cropHeight);
		self::outputImage($thumbImg, FileHelper::getExtension(basename($source)), $destination, $quality);
	}
}
