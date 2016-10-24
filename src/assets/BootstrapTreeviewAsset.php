<?php
/**
 * Created by PhpStorm.
 * User: phuon
 * Date: 9/21/2016
 * Time: 9:46 AM
 */
namespace navatech\roxymce\assets;

use yii\web\AssetBundle;
use yii\web\View;

class BootstrapTreeviewAsset extends AssetBundle {

	public $sourcePath = '@bower/bootstrap-treeview/dist';

	public $js         = ['bootstrap-treeview.min.js'];

	public $depends    = [
		'yii\bootstrap\BootstrapAsset',
		'yii\web\JqueryAsset',
	];

	public $jsOptions  = ['position' => View::POS_HEAD];
}