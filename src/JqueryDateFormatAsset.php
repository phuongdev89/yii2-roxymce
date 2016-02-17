<?php
/**
 * Created by Navatech.
 * @project yii-basic
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    17/02/2016
 * @time    12:04 CH
 */
namespace navatech\roxymce;

use yii\web\AssetBundle;
use yii\web\View;

class JqueryDateFormatAsset extends AssetBundle {

	public function init() {
		parent::init();
		$this->depends    = [
			'yii\web\JqueryAsset',
		];
		$this->sourcePath = '@bower/jquery-dateFormat';
		$this->js         = [
			'dist/jquery-dateFormat.min.js',
		];
		$this->jsOptions  = ['position' => View::POS_HEAD];
	}
}