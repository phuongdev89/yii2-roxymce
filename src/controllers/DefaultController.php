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

use Yii;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\web\Controller;
use yii\web\Response;

/**
 * {@inheritDoc}
 */
class DefaultController extends Controller {

	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}

	/**
	 * @return string
	 * @throws InvalidParamException
	 */
	public function actionIndex() {
		if (class_exists('navatech\localeurls\UrlManager')) {
			$roxyMceUrl = Yii::$app->homeUrl . Yii::$app->language . '/roxymce/';
		} else {
			$roxyMceUrl = Yii::$app->homeUrl . 'roxymce/';
		}
		return $this->renderAjax('index', ['roxyMceUrl' => $roxyMceUrl]);
	}

	/**
	 * @return mixed
	 * @throws Exception
	 */
	public function actionConfig() {
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return Yii::$app->getModule('roxymce')->config;
		} else {
			throw  new Exception('Unknown');
		}
	}
}