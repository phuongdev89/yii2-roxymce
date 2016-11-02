<?php
/**
 * Created by Navatech.
 * @project roxymce
 * @author  Le Phuong
 * @email   phuong17889[at]gmail.com
 * @date    17/02/2016
 * @time    12:09 CH
 * @version 2.0.0
 */
namespace navatech\roxymce\assets;

use yii\web\AssetBundle;

/**
 * This will register asset for FontAwesome
 * {@inheritDoc}
 */
class FontAwesomeAsset extends AssetBundle {

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		parent::init();
		$this->depends = [
			'yii\web\JqueryAsset',
		];
		if (file_exists(\Yii::getAlias('@bower/font-awesome'))) {
			$this->sourcePath = '@bower/font-awesome';
		} else {
			$this->sourcePath = '@bower/fontawesome';
		}
		$this->css = [
			'css/font-awesome.min.css',
		];
	}
}