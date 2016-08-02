<?php
/**
 * Created by PhpStorm.
 * User: notte
 * Date: 02/08/2016
 * Time: 9:30 SA
 */
namespace navatech\roxymce\controllers;

use navatech\roxymce\helpers\FolderHelper;
use Yii;
use yii\helpers\ArrayHelper;
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
		Yii::$app->response->format = Response::FORMAT_JSON;
		$data                       = [];
		$content                    = [];
		if ($f == null || $f == '') {
			$f      = Yii::getAlias(Yii::$app->getModule('roxymce')->uploadFolder);
			$data[] = FolderHelper::rootFolderName();
		}
		$data = ArrayHelper::merge($data, FolderHelper::listFolder($f));
		foreach ($data as $item) {
			$content[] = '<li>' . $item . '</li>';
		}
		return [
			'error'   => 0,
			'content' => $content,
		];
	}
}