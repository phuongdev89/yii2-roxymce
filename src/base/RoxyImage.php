<?php
/**
 * Created by Navatech.
 * @project yii2-roxymce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    16/02/2016
 * @time    9:06 CH
 */
namespace navatech\roxymce\base;

class RoxyImage {

	/**
	 * @param $path
	 *
	 * @return null|resource
	 */
	public static function GetImage($path) {
		$img = null;
		switch (RoxyFile::GetExtension(basename($path))) {
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
	 */
	public static function OutputImage($img, $type, $destination = '', $quality = 90) {
		if (is_string($img)) {
			$img = self::GetImage($img);
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
	 */
	public static function Resize($source, $destination, $width = 150, $height = 0, $quality = 90) {
		$tmp = getimagesize($source);
		$w   = $tmp[0];
		$h   = $tmp[1];
		$r   = $w / $h;
		if ($w <= ($width + 1) && (($h <= ($height + 1)) || (!$height && !$width))) {
			if ($source !== $destination) {
				self::OutputImage($source, RoxyFile::GetExtension(basename($source)), $destination, $quality);
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
		$img      = self::GetImage($source);
		imagecopyresampled($thumbImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $w, $h);
		self::OutputImage($thumbImg, RoxyFile::GetExtension(basename($source)), $destination, $quality);
	}

	/**
	 * @param     $source
	 * @param     $destination
	 * @param     $width
	 * @param     $height
	 * @param int $quality
	 */
	public static function CropCenter($source, $destination, $width, $height, $quality = 90) {
		$tmp = getimagesize($source);
		$w   = $tmp[0];
		$h   = $tmp[1];
		if (($w <= $width) && (!$height || ($h <= $height))) {
			self::OutputImage(self::GetImage($source), RoxyFile::GetExtension(basename($source)), $destination, $quality);
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
		self::Crop($source, $destination, $left, $top, $cropWidth, $cropHeight, $width, $height, $quality);
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
	 */
	public static function Crop($source, $destination, $x, $y, $cropWidth, $cropHeight, $width, $height, $quality = 90) {
		$thumbImg = imagecreatetruecolor($width, $height);
		$img      = self::GetImage($source);
		imagecopyresampled($thumbImg, $img, 0, 0, $x, $y, $width, $height, $cropWidth, $cropHeight);
		self::OutputImage($thumbImg, RoxyFile::GetExtension(basename($source)), $destination, $quality);
	}
}
