<?php
namespace navatech\roxymce;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Created by Navatech.
 * @project yii-basic
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    10:24 SA
 */
class TinyMceAsset extends AssetBundle {

	public function init() {
		parent::init();
		$this->depends    = [
			'yii\web\YiiAsset',
			'yii\bootstrap\BootstrapAsset',
			'yii\bootstrap\BootstrapPluginAsset',
		];
		$this->sourcePath = '@vendor/tinymce/tinymce';
		$this->js         = ['tinymce.js'];
		$this->jsOptions  = ['position' => View::POS_HEAD];
	}
}