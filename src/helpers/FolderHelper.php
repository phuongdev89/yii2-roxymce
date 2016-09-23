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
use yii\helpers\Url;

class FolderHelper {

	/**
	 * @param $path
	 *
	 * @return array|bool
	 */
	private static function _folderList($path) {
		$response = null;
		if (is_dir($path)) {
			$dirs = glob($path . '/*', GLOB_ONLYDIR);
			foreach ($dirs as $dir) {
				$array      = [
					'text'         => basename($dir),
					'path'         => $dir,
					'href'         => Url::to([
						'/roxymce/management/file-list',
						'folder' => $dir,
					]),
					'icon'         => 'glyphicon glyphicon-folder-close',
					'selectedIcon' => 'glyphicon glyphicon-folder-open',
					'nodes'        => self::_folderList($dir),
				];
				$response[] = $array;
			}
		}
		return $response;
	}

	/**
	 * @param $path
	 *
	 * @return array|bool
	 */
	public static function folderList($path) {
		$response[] = [
			'text'         => basename($path),
			'path'         => $path,
			'href'         => Url::to([
				'/roxymce/management/file-list',
				'folder' => $path,
			]),
			'icon'         => 'glyphicon glyphicon-folder-close',
			'selectedIcon' => 'glyphicon glyphicon-folder-open',
			'state'        => [
				'checked'  => true,
				'expanded' => true,
				'selected' => true,
			],
			'nodes'        => self::_folderList($path),
		];
		return $response;
	}

	/**
	 * @param $path
	 *
	 * @return array
	 */
	public static function fileList($path) {
		$result = array();
		$dirs   = scandir($path);
		foreach ($dirs as $key => $value) {
			if (!in_array($value, array(
				".",
				"..",
			))
			) {
				if (!is_dir($path . DIRECTORY_SEPARATOR . $value)) {
					$result[] = $value;
				}
			}
		}
		return $result;
	}

	/**
	 * @return string
	 */
	public static function rootFolderName() {
		$rootFolder = Yii::getAlias(Yii::$app->getModule('roxymce')->uploadFolder);
		return basename($rootFolder);
	}
}