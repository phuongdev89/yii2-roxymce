<?php
/**
 * Created by Navatech.
 * @project roxymce
 * @author  Le Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    4:19 CH
 * @version 2.0.0
 */
namespace navatech\roxymce\controllers;

use navatech\roxymce\models\UploadForm;
use navatech\roxymce\Module;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * {@inheritDoc}
 */
class DefaultController extends Controller {

	/**
	 * Render a view
	 * @return string
	 * @throws InvalidParamException
	 */
	public function actionIndex() {
		/**@var Module $module */
		$module        = Yii::$app->getModule('roxymce');
		$uploadForm    = new UploadForm();
		$defaultFolder = Url::to(['/roxymce/management/file-list']);
		if ($module->rememberLastFolder && Yii::$app->cache->exists('roxy_last_folder')) {
			$defaultFolder = Url::to([
				'/roxymce/management/file-list',
				'folder' => Yii::$app->cache->get('roxy_last_folder'),
			]);
		}
		return $this->renderAjax('index', [
			'module'        => $module,
			'uploadForm'    => $uploadForm,
			'defaultFolder' => $defaultFolder,
		]);
	}
}