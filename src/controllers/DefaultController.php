<?php
/**
 * Created by Navatech.
 * @project yii2-roxymce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    4:19 CH
 */
namespace navatech\roxymce\controllers;

use navatech\roxymce\RoxyMceAsset;
use Yii;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\web\Controller;
use yii\web\Response;
use yii\web\View;

class DefaultController extends Controller {

	public function filter() {
		return [
			'access',
		];
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
		$roxyMceAsset = RoxyMceAsset::register($this->getView());
		$this->view->registerJs('var roxyMceAsset = "' . $roxyMceAsset->baseUrl . '";var roxyMceUrl = "' . $roxyMceUrl . '";', View::POS_HEAD);
		//TODO should make another version of bootstrap
		return $this->renderAjax('index', ['roxyMceAsset' => $roxyMceAsset]);
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