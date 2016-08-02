<?php
/**
 * Created by Navatech.
 * @project yii2-roxymce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    4:52 CH
 */
namespace navatech\roxymce\helpers;

use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper as BaseFileHelper;
use ZipArchive;

/**
 * {@inheritDoc}
 */
class FileHelper extends BaseFileHelper {

	/**
	 * @param $dir
	 *
	 * @return bool
	 * @throws Exception
	 */
	public static function checkWritable($dir) {
		$ret = false;
		if (self::createPath($dir)) {
			$dir      = self::fixPath($dir . '/');
			$testFile = 'writetest.txt';
			$f        = @fopen($dir . $testFile, 'w', false);
			if ($f) {
				fclose($f);
				$ret = true;
				@unlink($dir . $testFile);
			}
		}
		return $ret;
	}

	/**
	 * @param $dir
	 *
	 * @return bool
	 * @throws Exception
	 */
	public static function createPath($dir) {
		if (!@mkdir($dir, 0777, true) && !@is_dir($dir)) {
			throw new Exception('Can not create directory');
		}
		return true;
	}

	/**
	 * @param $path
	 *
	 * @return string
	 */
	public static function fixPath($path) {
		return mb_ereg_replace('[\\\/]+', '/', $path);
	}

	/**
	 * @param $filename
	 *
	 * @return bool
	 * @throws InvalidConfigException
	 */
	public static function canUploadFile($filename) {
		$ret       = false;
		$forbidden = array_filter(preg_split('/[^\d\w]+/', strtolower(FORBIDDEN_UPLOADS)));
		$allowed   = array_filter(preg_split('/[^\d\w]+/', strtolower(ALLOWED_UPLOADS)));
		$ext       = self::getExtension($filename);
		if (($forbidden === null || !in_array($ext, $forbidden, true)) && ($allowed === null || in_array($ext, $allowed, true))) {
			$ret = true;
		}
		return $ret;
	}

	/**
	 * Returns file extension without dot
	 *
	 * @param string $filename
	 *
	 * @return string
	 */
	public static function getExtension($filename) {
		$ext = '';
		if (mb_strrpos($filename, '.') !== false) {
			$ext = mb_substr($filename, mb_strrpos($filename, '.') + 1);
		}
		return strtolower($ext);
	}

	/**
	 * @param        $path
	 * @param        $zipFile
	 * @param string $zipPath
	 */
	public static function zipDir($path, $zipFile, $zipPath = '') {
		$zip = new ZipArchive();
		$zip->open($zipFile, ZipArchive::CREATE);
		self::zipAddDir($path, $zip, $zipPath);
		$zip->close();
	}

	/**
	 * @param            $path
	 * @param ZipArchive $zip
	 * @param            $zipPath
	 */
	public static function zipAddDir($path, ZipArchive $zip, $zipPath) {
		$d       = opendir($path);
		$zipPath = str_replace('//', '/', $zipPath);
		if ($zipPath && $zipPath !== '/') {
			$zip->addEmptyDir($zipPath);
		}
		while (($f = readdir($d)) !== false) {
			if ($f === '.' || $f === '..') {
				continue;
			}
			$filePath = $path . '/' . $f;
			if (is_file($filePath)) {
				$zip->addFile($filePath, ($zipPath ? $zipPath . '/' : '') . $f);
			} elseif (is_dir($filePath)) {
				self::zipAddDir($filePath, $zip, ($zipPath ? $zipPath . '/' : '') . $f);
			}
		}
		closedir($d);
	}

	/**
	 * @param $fileName
	 *
	 * @return bool
	 * @throws InvalidConfigException
	 */
	public static function isImage($fileName) {
		$ret = false;
		$ext = strtolower(self::getExtension($fileName));
		if ($ext === 'jpg' || $ext === 'jpeg' || $ext === 'jpe' || $ext === 'png' || $ext === 'gif' || $ext === 'ico') {
			$ret = true;
		}
		return $ret;
	}

	/**
	 * @param $fileName
	 *
	 * @return bool
	 * @throws InvalidConfigException
	 */
	public static function isFlash($fileName) {
		$ret = false;
		$ext = strtolower(self::getExtension($fileName));
		if ($ext === 'swf' || $ext === 'flv' || $ext === 'swc' || $ext === 'swt') {
			$ret = true;
		}
		return $ret;
	}

	/**
	 * Returns human formated file size
	 *
	 * @param int $filesize
	 *
	 * @return string
	 */
	public static function formatFileSize($filesize) {
		$unit = 'B';
		if ($filesize > 1024) {
			$unit = 'KB';
			$filesize /= 1024;
		}
		if ($filesize > 1024) {
			$unit = 'MB';
			$filesize /= 1024;
		}
		if ($filesize > 1024) {
			$unit = 'GB';
			$filesize /= 1024;
		}
		return round($filesize, 2) . ' ' . $unit;
	}

	/**
	 * Returns MIME type of $filename
	 *
	 * @param string $filename
	 *
	 * @return string
	 */
	public static function getMimeType($filename) {
		$ext = self::getExtension($filename);
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
	 * @param $filename
	 *
	 * @return string
	 */
	public static function getFullName($filename) {
		$tmp = mb_strpos($filename, '?');
		if ($tmp !== false) {
			$filename = mb_substr($filename, 0, $tmp);
		}
		$filename = basename($filename);
		return $filename;
	}

	/**
	 * creates unique file name using $filename( " - Copy " and number is added if file already exists) in directory
	 * $dir
	 *
	 * @param $dir
	 * @param $filename
	 *
	 * @return string
	 * @throws InvalidConfigException
	 */
	public static function makeUniqueFilename($dir, $filename) {
		$dir .= '/';
		$dir  = self::fixPath($dir . '/');
		$ext  = self::getExtension($filename);
		$name = self::getName($filename);
		$name = self::cleanupFilename($name);
		$name = mb_ereg_replace(' \\- Copy \\d+$', '', $name);
		if ($ext) {
			$ext = '.' . $ext;
		}
		if (!$name) {
			$name = 'file';
		}
		$i = 0;
		do {
			$temp = ($i > 0 ? $name . " - Copy $i" : $name) . $ext;
			$i ++;
		} while (file_exists($dir . $temp));
		return $temp;
	}

	/**
	 * Returns file name without extension
	 *
	 * @param string $filename
	 *
	 * @return string
	 */
	public static function getName($filename) {
		$tmp = mb_strpos($filename, '?');
		if ($tmp !== false) {
			$filename = mb_substr($filename, 0, $tmp);
		}
		$dotPos = mb_strrpos($filename, '.');
		$name   = $filename;
		if ($dotPos !== false) {
			$name = mb_substr($filename, 0, $dotPos);
		}
		return $name;
	}

	/**
	 * Replaces any character that is not letter, digit or underscore from $filename with $sep
	 *
	 * @param string $filename
	 * @param string $sep
	 *
	 * @return string
	 * @throws InvalidConfigException
	 */
	public static function cleanupFilename($filename, $sep = '_') {
		$str = '';
		if (strpos($filename, '.')) {
			$ext  = self::getExtension($filename);
			$name = self::getName($filename);
		} else {
			$ext  = '';
			$name = $filename;
		}
		if (mb_strlen($name) > 32) {
			$name = mb_substr($name, 0, 32);
		}
		$str = str_replace('.php', '', $str);
		$str = mb_ereg_replace("[^\\w]", $str, $name);
		$str = mb_ereg_replace("$sep+", $sep, $str) . ($ext ? '.' . $ext : '');
		return $str;
	}

	/**
	 * creates unique directory name using $name( " - Copy " and number is added if directory already exists) in
	 * directory $dir
	 *
	 * @param string $dir
	 * @param string $name
	 *
	 * @return string
	 */
	public static function makeUniqueDirname($dir, $name) {
		$dir  = self::fixPath($dir . '/');
		$name = mb_ereg_replace(' - Copy \\d+$', '', $name);
		if (!$name) {
			$name = 'directory';
		}
		$i = 0;
		do {
			$temp = ($i ? $name . " - Copy $i" : $name);
			$i ++;
		} while (is_dir($dir . $temp));
		return $temp;
	}
}
