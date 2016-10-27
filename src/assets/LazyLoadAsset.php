<?php
/**
 * @project basic
 * @author  Le Phuong
 * @email phuong17889@gmail.com
 * @date    10/26/2016
 * @time    11:34 AM
 */
namespace navatech\roxymce\assets;

use yii\web\AssetBundle;
use yii\web\View;

class LazyLoadAsset extends AssetBundle {

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		parent::init();
		$this->js      = [
			'jquery.lazyload.js',
		];
		$this->depends = [
			'yii\web\JqueryAsset',
		];
		if (file_exists(\Yii::getAlias('@bower/jquery_lazyload'))) {
			$this->sourcePath = '@bower/jquery_lazyload';
		} else {
			$this->sourcePath = '@bower/jquery.lazyload';
		}
		$this->jsOptions = [
			'position' => View::POS_END,
		];
	}
}