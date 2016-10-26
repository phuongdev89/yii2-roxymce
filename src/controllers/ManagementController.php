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
use navatech\roxymce\models\UploadForm;
use navatech\roxymce\Module;
use Yii;
use yii\filters\ContentNegotiator;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * @property Module $module
 */
class ManagementController extends Controller {

	public $enableCsrfValidation = false;

	/**
	 * {@inheritDoc}
	 */
	public function behaviors() {
		$behaviors                      = parent::behaviors();
		$behaviors['contentNegotiator'] = [
			'class'   => ContentNegotiator::className(),
			'formats' => [
				'application/json' => Response::FORMAT_JSON,
			],
		];
		return $behaviors;
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
						'data'    => [
							'href' => Url::to([
								'/roxymce/management/file-list',
								'folder' => $folder . DIRECTORY_SEPARATOR . $name,
							]),
							'text' => $name,
							'path' => $folder . DIRECTORY_SEPARATOR . $name,
						],
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
		$content = FolderHelper::folderList($folder);
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
		$content = [];
		foreach (FolderHelper::fileList($folder) as $item) {
			$file      = $folder . DIRECTORY_SEPARATOR . $item;
			$content[] = [
				'is_image' => FileHelper::isImage($item),
				'url'      => FileHelper::fileUrl($file),
				'preview'  => FileHelper::filePreview($file),
				'icon'     => FileHelper::fileIcon($file),
				'name'     => $item,
				'size'     => FileHelper::fileSize(filesize($file), 0),
				'date'     => date('Y-m-d H:i:s', filemtime($file)),
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
		if ($folder == '') {
			return [
				'error'   => 1,
				'message' => Yii::t('roxy', 'Can not rename root folder'),
			];
		}
		$newFolder = dirname($folder) . DIRECTORY_SEPARATOR . $name;
		if (rename($folder, $newFolder)) {
			return [
				'error' => 0,
				'data'  => [
					'href' => Url::to([
						'/roxymce/management/file-list',
						'folder' => $newFolder,
					]),
					'text' => $name,
					'path' => $newFolder,
				],
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

	/**
	 * @param string $folder
	 *
	 * @return array
	 */
	public function actionFileUpload($folder = '') {
		if ($folder == '') {
			$folder = Yii::getAlias($this->module->uploadFolder);
		}
		if (is_dir($folder)) {
			$model       = new UploadForm();
			$model->file = UploadedFile::getInstance($model, 'file');
			if ($model->upload($folder)) {
				return [
					'error' => 0,
				];
			} else {
				if (isset($model->firstErrors['file'])) {
					return [
						'error'   => 1,
						'message' => $model->firstErrors['file'],
					];
				}
			}
		}
		return [
			'error'   => 1,
			'message' => Yii::t('roxy', 'Somethings went wrong'),
		];
	}
}