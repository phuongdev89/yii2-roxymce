<?php
/**
 * Created by Navatech.
 * @project yii2-roxymce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    4:45 CH
 */
namespace navatech\roxymce\helpers;

use navatech\roxymce\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;

/**
 * RoxyHelper is core functions of Roxy file man
 */
class RoxyHelper {

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
	 * @throws InvalidParamException
	 */
	public static function t($key) {
		$file     = Yii::$app->language . '.json';
		$langPath = Yii::getAlias('@vendor/navatech/yii2-roxymce/src/assets/lang');
		if (defined('LANG')) {
			$file = LANG . '.json';
		}
		$file = $langPath . DIRECTORY_SEPARATOR . $file;
		$LANG = json_decode(file_get_contents($file), true);
		return $LANG[$key];
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
	 * @param        $type
	 * @param string $str
	 *
	 * @return string
	 */
	public static function gerResultStr($type, $str = '') {
		return '{"res":"' . addslashes($type) . '","msg":"' . addslashes($str) . '"}';
	}

	public static function getDirs($path, $type, $response = []) {
		$ret   = $sort = [];
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
			$ret[$fullPath]  = [
				'path'  => $fullPath,
				'files' => $tmp['files'],
				'dirs'  => $tmp['dirs'],
			];
			$sort[$fullPath] = $f;
		}
		natcasesort($sort);
		foreach ($sort as $k => $v) {
			$tmp        = $ret[$k];
			$response[] = [
				'p' => mb_ereg_replace('"', '\\"', $tmp['path']),
				'f' => $tmp['files'],
				'd' => $tmp['dirs'],
			];
			$response   = self::getDirs($tmp['path'], $type, $response);
		}
		return $response;
	}

	/**
	 * @param $path
	 *
	 * @return array
	 */
	public static function listDirectory($path) {
		$ret = @scandir($path);
		if ($ret === false) {
			$ret = [];
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
	 *
	 * @return mixed|string
	 * @throws InvalidParamException
	 */
	public static function fixPath($path) {
		$path = Yii::getAlias($path);
		$path = str_replace('\\', '/', $path);
		$path = FileHelper::fixPath($path);
		return $path;
	}

	/**
	 * @param $path
	 * @param $type
	 *
	 * @return array
	 * @throws InvalidConfigException
	 */
	public static function getFilesNumber($path, $type) {
		$files = 0;
		$dirs  = 0;
		$tmp   = self::listDirectory($path);
		foreach ($tmp as $ff) {
			if ($ff === '.' || $ff === '..') {
				continue;
			} elseif (is_file($path . '/' . $ff) && ($type === '' || ($type === 'image' && FileHelper::isImage($ff)) || ($type === 'flash' && FileHelper::isFlash($ff)))) {
				$files ++;
			} elseif (is_dir($path . '/' . $ff)) {
				$dirs ++;
			}
		}
		return [
			'files' => $files,
			'dirs'  => $dirs,
		];
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
	 * @return mixed|string
	 * @throws InvalidParamException
	 */
	public static function getFilesPath() {
		$ret = Module::isAdvanced() ? FileHelper::fixPath(Yii::getAlias('@frontend/web/') . FILES_ROOT) : FileHelper::fixPath(Yii::getAlias('@app/web/') . FILES_ROOT);
		$tmp = $_SERVER['DOCUMENT_ROOT'];
		if (in_array(mb_substr($tmp, - 1), [
			'/',
			'\\',
		], true)) {
			$tmp = mb_substr($tmp, 0, - 1);
		}
		$ret = str_replace(FileHelper::fixPath($tmp), '', $ret);
		return $ret;
	}

	/**
	 * @param string $str
	 *
	 * @return string
	 */
	public static function getErrorRes($str = '') {
		return self::gerResultStr('error', $str);
	}
}
