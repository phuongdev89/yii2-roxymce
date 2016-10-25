<?php
/**
 * Created by Navatech.
 * @project yii2-roxymce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    4:19 CH
 * @version 1.0.0
 */
namespace navatech\roxymce\controllers;

use navatech\roxymce\models\UploadForm;
use Yii;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\web\Controller;
use yii\web\Response;

/**
 * {@inheritDoc}
 */
class DefaultController extends Controller {

	/**
	 * {@inheritDoc}
	 */
	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}

	/**
	 * Render a view
	 * @return string
	 * @throws InvalidParamException
	 */
	public function actionIndex() {
		$module     = Yii::$app->getModule('roxymce');
		$uploadForm = new UploadForm();
		return $this->renderAjax('index', [
			'module'     => $module,
			'uploadForm' => $uploadForm,
		]);
	}

	/**
	 * Return all default config
	 * @return mixed
	 * @throws Exception
	 */
	public function actionConfig() {
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return Yii::$app->getModule('roxymce')->config;
		} else {
			throw new Exception('Unknown');
		}
	}
}