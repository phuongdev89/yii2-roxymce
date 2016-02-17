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
class RoxyMceAsset extends AssetBundle {

	public function init() {
		parent::init();
		$this->sourcePath = '@vendor/navatech/yii2-roxymce/src/assets';
		$this->depends    = [
			'yii\web\JqueryAsset',
		];
		$this->css        = [
			'css/main.css',
		];
		$this->js         = [
			'js/filetypes.js',
			'js/custom.js',
			'js/main.js',
			'js/utils.js',
			'js/file.js',
			'js/directory.js',
		];
		$this->jsOptions  = ['position' => View::POS_HEAD];
	}
}