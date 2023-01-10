<?php
/**
 * Created by phuongdev89
 * @project hdchonloc
 * @author  Phuong Dev
 * @email phuongdev89@gmail.com
 * @time    12/7/2016 11:05 AM
 */

namespace phuongdev89\roxymce\assets;

use yii\web\AssetBundle;
use yii\web\View;

class ContextMenuAsset extends AssetBundle
{

    public $sourcePath = '@vendor/phuongdev89/yii2-roxymce/src/web';

    public $css = [
        'css/jquery.contextMenu.min.css',
    ];

    public $js = [
        'js/jquery.contextMenu.min.js',
    ];

    public $jsOptions = ['position' => View::POS_HEAD];
}
