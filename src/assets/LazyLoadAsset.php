<?php
/**
 * Created by Navatech.
 * @project roxymce
 * @author  Le Phuong
 * @email   phuong17889[at]gmail.com
 * @date    28/10/2016
 * @time    2:39 CH
 * @version 2.0.0
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
			'position' => View::POS_HEAD,
		];
	}
}