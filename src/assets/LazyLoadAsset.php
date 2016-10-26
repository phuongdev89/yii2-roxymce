<?php
/**
 * @project basic
 * @author  Le Phuong
 * @email phuong17889@gmail.com
 * @date    10/26/2016
 * @time    11:34 AM
 */
namespace navatech\roxymce\assets;

use yii\web\AssetBundle;
use yii\web\View;

class LazyLoadAsset extends AssetBundle {

	public $js         = [
		'jquery.lazyload.js',
	];

	public $sourcePath = '@bower/jquery_lazyload';

	public $jsOptions  = ['position' => View::POS_END];
}