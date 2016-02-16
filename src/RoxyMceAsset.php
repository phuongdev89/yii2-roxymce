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
		$this->css        = [
			'css/jquery-ui-1.10.4.custom.min.css',
			'css/main.css',
		];
		$this->js         = [
			'js/jquery-1.11.1.min.js',
			'js/jquery-ui-1.10.4.custom.min.js',
			'js/filetypes.js',
			'js/custom.js',
			'js/main.js',
			'js/utils.js',
			'js/file.js',
			'js/directory.js',
			'js/jquery-dateFormat.min.js',
		];
		$this->jsOptions  = ['position' => View::POS_HEAD];
	}
}