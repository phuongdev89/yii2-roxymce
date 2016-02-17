<?php
/**
 * Created by Navatech.
 * @project yii-basic
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    17/02/2016
 * @time    12:09 CH
 */
namespace navatech\roxymce;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle {

	public function init() {
		parent::init();
		$this->depends    = [
			'yii\web\JqueryAsset',
		];
		$this->sourcePath = '@bower/fontawesome';
		$this->css        = [
			'css/font-awesome.min.css',
		];
	}
}