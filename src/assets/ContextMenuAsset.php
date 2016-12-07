<?php
/**
 * Created by Navatech
 * @project hdchonloc
 * @author  Le Phuong
 * @email phuong17889@gmail.com
 * @time    12/7/2016 11:05 AM
 */
namespace navatech\roxymce\assets;

use yii\web\AssetBundle;
use yii\web\View;

class ContextMenuAsset extends AssetBundle {

	public $sourcePath = '@vendor/navatech/yii2-roxymce/src/web';

	public $css        = [
		'css/jquery.contextMenu.min.css',
	];

	public $js         = [
		'js/jquery.contextMenu.min.js',
	];

	public $jsOptions  = ['position' => View::POS_HEAD];
}