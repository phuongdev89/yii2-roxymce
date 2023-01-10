<?php
/**
 * Created by phuongdev89.
 * @project roxymce
 * @author  Phuong Dev
 * @email   phuongdev89@gmail.com
 * @date    28/10/2016
 * @time    2:39 CH
 * @version 2.0.0
 */

namespace phuongdev89\roxymce\assets;

use yii\web\AssetBundle;
use yii\web\View;

class BootstrapTreeviewAsset extends AssetBundle
{

    public $sourcePath = '@bower/patternfly-bootstrap-treeview/dist';

    public $js = ['bootstrap-treeview.min.js'];

    public $css = ['bootstrap-treeview.min.css'];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];

    public $jsOptions = ['position' => View::POS_HEAD];
}
