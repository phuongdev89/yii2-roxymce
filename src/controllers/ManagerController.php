<?php
/**
 * Created by Navatech.
 * @project yii2-roxymce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    4:38 CH
 */
namespace navatech\roxymce\controllers;

use yii\web\Controller;

class ManagerController extends Controller {

	private function getFilesNumber($path, $type) {
		$files = 0;
		$dirs  = 0;
		$tmp   = listDirectory($path);
		foreach ($tmp as $ff) {
			if ($ff == '.' || $ff == '..') {
				continue;
			} elseif (is_file($path . '/' . $ff) && ($type == '' || ($type == 'image' && RoxyFile::IsImage($ff)) || ($type == 'flash' && RoxyFile::IsFlash($ff)))) {
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

	private function GetDirs($path, $type) {
		$ret   = $sort = array();
		$files = listDirectory(fixPath($path), 0);
		foreach ($files as $f) {
			$fullPath = $path . '/' . $f;
			if (!is_dir(fixPath($fullPath)) || $f == '.' || $f == '..') {
				continue;
			}
			$tmp             = getFilesNumber(fixPath($fullPath), $type);
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
			GetDirs($tmp['path'], $type);
		}
	}

	public function actionDirlist() {
		verifyAction('DIRLIST');
		checkAccess('DIRLIST');
		$type = (empty($_GET['type']) ? '' : strtolower($_GET['type']));
		if ($type != 'image' && $type != 'flash') {
			$type = '';
		}
		echo "[$\n";
		$tmp = $this->getFilesNumber(fixPath(getFilesPath()), $type);
		echo '{"p":"' . mb_ereg_replace('"', '\\"', getFilesPath()) . '","f":"' . $tmp['files'] . '","d":"' . $tmp['dirs'] . '"}';
		$this->GetDirs(getFilesPath(), $type);
		echo "\n]";
	}
}