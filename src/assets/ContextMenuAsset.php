<?php
/**
 * Created by Navatech.
 * @project baoviet-insurance
 * @author  Phuong
 * @email   notteen[at]gmail.com
 * @date    28/10/2016
 * @time    2:39 CH
 */
namespace navatech\roxymce\assets;

use yii\web\AssetBundle;
use yii\web\View;

class ContextMenuAsset extends AssetBundle {

	public $js         = ['jquery.contextMenu.js'];

	public $css        = ['jquery.contextMenu.css'];

	public $sourcePath = '@bower/jQuery-contextMenu/dist';

	public $jsOptions  = ['position' => View::POS_HEAD];
}