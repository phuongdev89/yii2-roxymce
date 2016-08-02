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

use Yii;
use yii\base\ErrorException;

class FolderHelper {

	/**
	 * @param $path
	 *
	 * @return array|bool
	 */
	public static function listFolder($path) {
		$ret = preg_grep('/^([^.])/', scandir($path));
		try {
			if ($ret === false) {
				$ret = [];
				$d   = opendir($path);
				if ($d) {
					while (($f = readdir($d)) !== false) {
						echo $f;
						$ret[] = $f;
					}
					closedir($d);
				}
			}
		} catch (ErrorException $e) {
			return [];
		}
		return $ret;
	}

	/**
	 * @return string
	 */
	public static function rootFolderName() {
		$rootFolder = Yii::getAlias(Yii::$app->getModule('roxymce')->uploadFolder);
		return basename($rootFolder);
	}
}
