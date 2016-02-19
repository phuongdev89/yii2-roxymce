<?php
/**
 * Created by Navatech.
 * @project RoxyMce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    10:24 SA
 * @version 1.0.0
 */
namespace navatech\roxymce;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * {@inheritDoc}
 */
class RoxyMceAsset extends AssetBundle {

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		parent::init();
		$this->sourcePath = '@vendor/navatech/yii2-roxymce/src/assets';
		$this->depends    = [
			'yii\web\JqueryAsset',
		];
		$this->css        = [
			'css/jquery-ui-1.10.0.custom.css',
			'css/main.css',
		];
		$this->js         = [
			'js/filetypes.js',
			'js/main.js',
			'js/utils.js',
			'js/file.js',
			'js/directory.js',
			'js/custom.js',
			'js/jquery-ui.js',
		];
		$this->jsOptions  = ['position' => View::POS_HEAD];
	}
}