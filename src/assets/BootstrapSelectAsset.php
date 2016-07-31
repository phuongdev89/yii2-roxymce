<?php
/**
 * Created by PhpStorm.
 * User: notte
 * Date: 31/07/2016
 * Time: 12:40 CH
 */
namespace navatech\roxymce\assets;

use yii\web\AssetBundle;

class BootstrapSelectAsset extends AssetBundle {

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		parent::init();
		$this->depends    = [
			'yii\bootstrap\BootstrapAsset',
		];
		$this->sourcePath = '@bower/bootstrap-select/dist';
		$this->css        = [
			'css/bootstrap-select.min.css',
		];
		$this->js         = [
			'js/bootstrap-select.min.js',
		];
	}
}