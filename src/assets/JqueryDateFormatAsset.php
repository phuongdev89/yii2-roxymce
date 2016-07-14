<?php
/**
 * Created by Navatech.
 * @project RoxyMce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    17/02/2016
 * @time    12:04 CH
 * @version 1.0.0
 */
namespace navatech\roxymce\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * {@inheritDoc}
 */
class JqueryDateFormatAsset extends AssetBundle {

	/**
	 * {@inheritDoc}
	 */
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