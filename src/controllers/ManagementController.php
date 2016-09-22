<?php
/**
 * Created by PhpStorm.
 * User: notte
 * Date: 02/08/2016
 * Time: 9:30 SA
 */
namespace navatech\roxymce\controllers;

use navatech\roxymce\helpers\FileHelper;
use navatech\roxymce\helpers\FolderHelper;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class ManagementController extends Controller {

	/**
	 * {@inheritDoc}
	 */
	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}

	/**
	 * @param $f string current directory
	 * @param $n string new dicrectory's name
	 *
	 * @return array
	 */
	public function actionCreateFolder($f, $n) {
		if (is_dir($f)) {
			if (mkdir($f . DIRECTORY_SEPARATOR . $n)) {
				$response = [
					'error'   => 0,
					'message' => Yii::t('roxy', 'Folder created'),
				];
			} else {
				$response = [
					'error'   => 1,
					'message' => Yii::t('roxy', 'Can\'t create folder in {0}', [$f]),
				];
			}
		} else {
			$response = [
				'error'   => 1,
				'message' => Yii::t('roxy', 'Invalid directory {0}', [$f]),
			];
		}
		Yii::$app->response->format = Response::FORMAT_JSON;
		return $response;
	}

	public function actionFolderList($f = '') {
		if ($f == '') {
			$f = Yii::getAlias($this->module->uploadFolder);
		}
		Yii::$app->response->format = Response::FORMAT_JSON;
		$content                    = FolderHelper::folderList($f);
		return [
			'error'   => 0,
			'content' => $content,
		];
	}

	public function actionFileList($type = 'thumb', $f = '') {
		if ($f == '') {
			$f = Yii::getAlias($this->module->uploadFolder);
		}
		Yii::$app->response->format = Response::FORMAT_JSON;
		$content                    = '';
		foreach (FolderHelper::fileList($f) as $item) {
			$file = $f . DIRECTORY_SEPARATOR . $item;
			if ($type == 'thumb') {
				$content .= '<li class="col-sm-3 thumb"><div class="thumb">';
				$content .= '<div class="file-preview"><img src="' . FileHelper::filepreview($file) . '"></div>';
				$content .= '<div class="file-name">' . $item . '</div>';
				$content .= '<div class="file-size">' . FileHelper::filesize(filesize($file), 0) . '</div>';
				$content .= '</div></li>';
			} else {
				$content .= '<li class="list">';
				$content .= '<div class="col-sm-6 file-name"><img class="icon" src="' . FileHelper::fileicon($file) . '">' . $item . '</div>';
				$content .= '<div class="col-sm-2 file-size">' . FileHelper::filesize(filesize($file), 0) . '</div>';
				$content .= '<div class="col-sm-4 file-date">' . date('Y-m-d H:i:s', filemtime($file)) . '</div>';
				$content .= '</li>';
			}
		}
		return [
			'error'   => 0,
			'content' => $content,
		];
	}
}