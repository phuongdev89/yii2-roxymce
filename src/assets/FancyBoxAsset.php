<?php
/**
 * Created by Navatech.
 * @project roxymce
 * @author  Le Phuong
 * @email   phuong17889[at]gmail.com
 * @date    28/10/2016
 * @time    2:39 CH
 * @version 2.0.0
 */
namespace navatech\roxymce\assets;

use yii\web\AssetBundle;
use yii\web\View;

class FancyBoxAsset extends AssetBundle {

	public $sourcePath = '@bower/fancybox/source';

	public $js         = ['jquery.fancybox.pack.js'];

	public $css        = ['jquery.fancybox.css'];

	public $jsOptions  = ['position' => View::POS_HEAD];
}