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
use navatech\roxymce\Module;
use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * @property Module $module
 */
class ManagementController extends Controller {

	/**
	 * {@inheritDoc}
	 */
	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}

	/**
	 * @param        $name
	 * @param string $folder
	 *
	 * @return array
	 */
	public function actionFolderCreate($name, $folder = '') {
		if ($folder == '') {
			$folder = Yii::getAlias($this->module->uploadFolder);
		}
		if (is_dir($folder)) {
			if (file_exists($folder . DIRECTORY_SEPARATOR . $name)) {
				$response = [
					'error'   => 1,
					'message' => Yii::t('roxy', 'Folder existed'),
				];
			} else {
				if (mkdir($folder . DIRECTORY_SEPARATOR . $name, 0777, true)) {
					$response = [
						'error'   => 0,
						'message' => Yii::t('roxy', 'Folder created'),
					];
				} else {
					$response = [
						'error'   => 1,
						'message' => Yii::t('roxy', 'Can\'t create folder in {0}', [$folder]),
					];
				}
			}
		} else {
			$response = [
				'error'   => 1,
				'message' => Yii::t('roxy', 'Invalid directory {0}', [$folder]),
			];
		}
		Yii::$app->response->format = Response::FORMAT_JSON;
		return $response;
	}

	/**
	 * @param string $folder
	 *
	 * @return array
	 */
	public function actionFolderList($folder = '') {
		if ($folder == '') {
			$folder = Yii::getAlias($this->module->uploadFolder);
		}
		Yii::$app->response->format = Response::FORMAT_JSON;
		$content                    = FolderHelper::folderList($folder);
		return [
			'error'   => 0,
			'content' => $content,
		];
	}

	/**
	 * @param string $folder
	 *
	 * @return array
	 */
	public function actionFileList($folder = '') {
		if ($folder == '') {
			$folder = Yii::getAlias($this->module->uploadFolder);
		}
		Yii::$app->response->format = Response::FORMAT_JSON;
		$content                    = [];
		foreach (FolderHelper::fileList($folder) as $item) {
			$file      = $folder . DIRECTORY_SEPARATOR . $item;
			$content[] = [
				'preview' => FileHelper::filepreview($file),
				'icon'    => FileHelper::fileicon($file),
				'name'    => $item,
				'size'    => FileHelper::filesize(filesize($file), 0),
				'date'    => date('Y-m-d H:i:s', filemtime($file)),
			];
		}
		return [
			'error'   => 0,
			'content' => $content,
		];
	}

	/**
	 * @param string $folder
	 * @param        $name
	 *
	 * @return array
	 */
	public function actionFolderRename($folder = '', $name) {
		Yii::$app->response->format = Response::FORMAT_JSON;
		if ($folder == '') {
			return [
				'error'   => 0,
				'message' => Yii::t('roxy', 'Can not rename root folder'),
			];
		}
		if (rename($folder, dirname($folder) . DIRECTORY_SEPARATOR . $name)) {
			return [
				'error'   => 0,
				'content' => $name,
			];
		} else {
			return [
				'error'   => 1,
				'message' => Yii::t('roxy', 'Somethings went wrong'),
			];
		}
	}

	/**
	 * @param        $folder
	 * @param string $parentFolder
	 *
	 * @return array
	 */
	public function actionFolderRemove($folder, $parentFolder = '') {
		Yii::$app->response->format = Response::FORMAT_JSON;
		if (rmdir($folder)) {
			return [
				'error'   => 0,
				'content' => $parentFolder,
			];
		} else {
			return [
				'error'   => 1,
				'message' => Yii::t('roxy', 'Somethings went wrong'),
			];
		}
	}
}