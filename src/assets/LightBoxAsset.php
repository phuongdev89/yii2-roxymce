<?php
/**
 * @project basic
 * @author  Le Phuong
 * @email phuong17889@gmail.com
 * @date    10/26/2016
 * @time    2:32 PM
 */
namespace navatech\roxymce\assets;

use yii\web\AssetBundle;

class LightBoxAsset extends AssetBundle {

	public $sourcePath = '@bower/lightbox2/dist';

	public $js         = ['js/lightbox.min.js'];

	public $css        = ['css/lightbox.min.css'];
}