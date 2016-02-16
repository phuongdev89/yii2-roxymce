<?php
/**
 * Created by Navatech.
 * @project yii2-roxymce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    4:45 CH
 */
namespace navatech\roxymce\base;

use yii\base\InvalidParamException;

class RoxyBase {

	/**
	 * @param $action
	 */
	public static function checkAccess($action) {
		if (!session_id($action)) {
			session_start();
		}
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public static function t($key) {
		global $LANG;
		if ($LANG === null) {
			$file     = 'en.json';
			$langPath = '../lang/';
			if (defined('LANG')) {
				if (LANG === 'auto') {
					$lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
					if (is_file($langPath . $lang . '.json')) {
						$file = $lang . '.json';
					}
				} elseif (is_file($langPath . LANG . '.json')) {
					$file = LANG . '.json';
				}
			}
			$file = $langPath . $file;
			$LANG = json_decode(file_get_contents($file), true);
		}
		if (!$LANG[$key]) {
			$LANG[$key] = $key;
		}
		return $LANG[$key];
	}

	/**
	 * @param $path
	 *
	 * @return bool
	 * @throws InvalidParamException
	 */
	public static function checkPath($path) {
		$ret = false;
		if (mb_strpos($path . '/', self::getFilesPath()) === 0) {
			$ret = true;
		}
		return $ret;
	}

	/**
	 * @param $path
	 *
	 * @return mixed|string
	 */
	public static function fixPath($path) {
		$path = $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
		$path = str_replace('\\', '/', $path);
		$path = RoxyFile::FixPath($path);
		return $path;
	}

	/**
	 * @param        $type
	 * @param string $str
	 *
	 * @return string
	 */
	public static function gerResultStr($type, $str = '') {
		return '{"res":"' . addslashes($type) . '","msg":"' . addslashes($str) . '"}';
	}

	/**
	 * @param string $str
	 *
	 * @return string
	 */
	public static function getSuccessRes($str = '') {
		return self::gerResultStr('ok', $str);
	}

	/**
	 * @param string $str
	 *
	 * @return string
	 */
	public static function getErrorRes($str = '') {
		return self::gerResultStr('error', $str);
	}

	/**
	 * @return mixed|string
	 * @throws InvalidParamException
	 */
	public static function getFilesPath() {
		$ret = RoxyFile::FixPath(\Yii::$app->basePath . \Yii::getAlias('@web/') . FILES_ROOT);
		$tmp = $_SERVER['DOCUMENT_ROOT'];
		if (in_array(mb_substr($tmp, - 1), [
			'/',
			'\\',
		], true)) {
			$tmp = mb_substr($tmp, 0, - 1);
		}
		$ret = str_replace(RoxyFile::FixPath($tmp), '', $ret);
		return $ret;
	}

	/**
	 * @param $path
	 *
	 * @return array
	 */
	public static function listDirectory($path) {
		$ret = @scandir($path);
		if ($ret === false) {
			$ret = array();
			$d   = opendir($path);
			if ($d) {
				while (($f = readdir($d)) !== false) {
					$ret[] = $f;
				}
				closedir($d);
			}
		}
		return $ret;
	}

	/**
	 * @param $path
	 * @param $type
	 *
	 * @return array
	 */
	public static function getFilesNumber($path, $type) {
		$files = 0;
		$dirs  = 0;
		$tmp   = self::listDirectory($path);
		foreach ($tmp as $ff) {
			if ($ff === '.' || $ff === '..') {
				continue;
			} elseif (is_file($path . '/' . $ff) && ($type === '' || ($type === 'image' && RoxyFile::IsImage($ff)) || ($type === 'flash' && RoxyFile::IsFlash($ff)))) {
				$files ++;
			} elseif (is_dir($path . '/' . $ff)) {
				$dirs ++;
			}
		}
		return array(
			'files' => $files,
			'dirs'  => $dirs,
		);
	}

	/**
	 * @param $path
	 * @param $type
	 */
	public static function GetDirs($path, $type) {
		$ret   = $sort = array();
		$files = self::listDirectory(self::fixPath($path));
		foreach ($files as $f) {
			$fullPath = $path . '/' . $f;
			if (in_array($f, [
					'.',
					'..',
				], true) || !is_dir(self::fixPath($fullPath))
			) {
				continue;
			}
			$tmp             = self::getFilesNumber(self::fixPath($fullPath), $type);
			$ret[$fullPath]  = array(
				'path'  => $fullPath,
				'files' => $tmp['files'],
				'dirs'  => $tmp['dirs'],
			);
			$sort[$fullPath] = $f;
		}
		natcasesort($sort);
		foreach ($sort as $k => $v) {
			$tmp = $ret[$k];
			echo ',{"p":"' . mb_ereg_replace('"', '\\"', $tmp['path']) . '","f":"' . $tmp['files'] . '","d":"' . $tmp['dirs'] . '"}';
			self::GetDirs($tmp['path'], $type);
		}
	}

	/**
	 * @param $path
	 *
	 * @throws InvalidParamException
	 */
	public static function verifyPath($path) {
		if (!self::checkPath($path)) {
			echo self::getErrorRes("Access to $path is denied") . ' ' . $path;
			exit;
		}
	}
}