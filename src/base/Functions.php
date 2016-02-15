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
class Functions {

	public function checkAccess() {
		if (!session_id()) {
			session_start();
		}
	}

	public function t($key) {
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

	public function checkPath($path) {
		$ret = false;
		if (mb_strpos($path . '/', getFilesPath()) === 0) {
			$ret = true;
		}
		return $ret;
	}

	public function verifyAction($action) {
		if (!defined($action) || !constant($action)) {
			exit;
		} else {
			$confUrl = constant($action);
			$qStr    = mb_strpos($confUrl, '?');
			if ($qStr !== false) {
				$confUrl = mb_substr($confUrl, 0, $qStr);
			}
			$confUrl = BASE_PATH . '/' . $confUrl;
			$confUrl = RoxyFile::FixPath($confUrl);
			$thisUrl = __DIR__ . '/' . basename($_SERVER['PHP_SELF']);
			$thisUrl = RoxyFile::FixPath($thisUrl);
			if ($thisUrl !== $confUrl) {
				echo "$confUrl $thisUrl";
				exit;
			}
		}
	}

	public function verifyPath($path) {
		if (!checkPath($path)) {
			echo getErrorRes("Access to $path is denied") . ' ' . $path;
			exit;
		}
	}

	public function fixPath($path) {
		$path = $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
		$path = str_replace('\\', '/', $path);
		$path = RoxyFile::FixPath($path);
		return $path;
	}

	public function gerResultStr($type, $str = '') {
		return '{"res":"' . addslashes($type) . '","msg":"' . addslashes($str) . '"}';
	}

	public function getSuccessRes($str = '') {
		return gerResultStr('ok', $str);
	}

	public function getErrorRes($str = '') {
		return gerResultStr('error', $str);
	}

	public function getFilesPath() {
		$ret = (array_key_exists(SESSION_PATH_KEY, $_SESSION) && $_SESSION[SESSION_PATH_KEY] !== '' ? $_SESSION[SESSION_PATH_KEY] : FILES_ROOT);
		if (!$ret) {
			$ret = RoxyFile::FixPath(BASE_PATH . '/Uploads');
			$tmp = $_SERVER['DOCUMENT_ROOT'];
			if (in_array(mb_substr($tmp, - 1), [
				'/',
				'\\',
			], true)) {
				$tmp = mb_substr($tmp, 0, - 1);
			}
			$ret = str_replace(RoxyFile::FixPath($tmp), '', $ret);
		}
		return $ret;
	}

	public function listDirectory($path) {
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
}