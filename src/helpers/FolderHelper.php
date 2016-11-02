<?php
/**
 * Created by Navatech.
 * @project roxymce
 * @author  Le Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    4:45 CH
 * @version 2.0.0
 */
namespace navatech\roxymce\helpers;

use navatech\roxymce\Module;
use Yii;
use yii\helpers\Url;

class FolderHelper {

	const SORT_DATE_ASC  = 1;

	const SORT_DATE_DESC = 2;

	const SORT_NAME_ASC  = 3;

	const SORT_NAME_DESC = 4;

	const SORT_SIZE_ASC  = 5;

	const SORT_SIZE_DESC = 6;

	/**
	 * @param $path
	 *
	 * @return array|bool
	 */
	private static function _folderList($path) {
		$path = realpath($path);
		/**@var Module $module */
		$module   = Yii::$app->getModule('roxymce');
		$response = null;
		if (is_dir($path)) {
			$dirs = glob($path . '/*', GLOB_ONLYDIR);
			foreach ($dirs as $dir) {
				$dir   = realpath($dir);
				$state = [
					'checked'  => false,
					'selected' => false,
				];
				if ($module->rememberLastFolder && Yii::$app->cache->exists('roxy_last_folder')) {
					$state = [
						'checked'  => $dir == realpath(Yii::$app->cache->get('roxy_last_folder')),
						'selected' => $dir == realpath(Yii::$app->cache->get('roxy_last_folder')),
					];
				}
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
					'state'        => $state,
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
		$path = realpath($path);
		/**@var Module $module */
		$module = Yii::$app->getModule('roxymce');
		$state  = [
			'checked'  => true,
			'expanded' => true,
			'selected' => true,
		];
		if ($module->rememberLastFolder && Yii::$app->cache->exists('roxy_last_folder')) {
			$state = [
				'checked'  => $path == realpath(Yii::$app->cache->get('roxy_last_folder')),
				'selected' => $path == realpath(Yii::$app->cache->get('roxy_last_folder')),
				'expanded' => true,
			];
		}
		$response[] = [
			'text'         => basename($path),
			'path'         => $path,
			'href'         => Url::to([
				'/roxymce/management/file-list',
				'folder' => $path,
			]),
			'icon'         => 'glyphicon glyphicon-folder-close',
			'selectedIcon' => 'glyphicon glyphicon-folder-open',
			'state'        => $state,
			'nodes'        => self::_folderList($path),
		];
		return $response;
	}

	/**
	 * @param     $path
	 *
	 * @param int $sort
	 *
	 * @return array
	 */
	public static function fileList($path, $sort = self::SORT_DATE_DESC) {
		$path    = realpath($path);
		$ignored = '.|..|.svn|.htaccess|.ftpquota|robots.txt|.idea|.git';
		$files   = array();
		foreach (scandir($path) as $file) {
			if (in_array($file, explode('|', $ignored)) || is_dir($path . DIRECTORY_SEPARATOR . $file)) {
				continue;
			}
			if (in_array($sort, [
				self::SORT_DATE_DESC,
				self::SORT_DATE_ASC,
			])) {
				$files[$file] = filemtime($path . '/' . $file);
			}
		}
		if (in_array($sort, [
			self::SORT_DATE_DESC,
			self::SORT_NAME_DESC,
			self::SORT_SIZE_DESC,
		])) {
			arsort($files);
		} else {
			asort($files);
		}
		$files = array_keys($files);
		return $files;
	}

	/**
	 * @return string
	 */
	public static function rootFolderName() {
		$rootFolder = Yii::getAlias(Yii::$app->getModule('roxymce')->uploadFolder);
		return basename($rootFolder);
	}
}