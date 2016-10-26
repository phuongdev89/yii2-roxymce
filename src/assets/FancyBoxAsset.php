<?php
/**
 * @project basic
 * @author  Le Phuong
 * @email phuong17889@gmail.com
 * @date    10/26/2016
 * @time    4:39 PM
 */
namespace navatech\roxymce\assets;
use yii\web\AssetBundle;

class FancyBoxAsset extends AssetBundle {

	public $sourcePath = '@bower/fancybox/source';

	public $js         = ['jquery.fancybox.pack.js'];

	public $css        = ['jquery.fancybox.css'];

}