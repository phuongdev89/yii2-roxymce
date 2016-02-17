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
		];
		$this->sourcePath = '@bower/tinymce-dist';
		$this->js         = ['tinymce.min.js'];
		$this->jsOptions  = ['position' => View::POS_HEAD];
	}
}