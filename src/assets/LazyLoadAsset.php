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

use Yii;
use yii\web\AssetBundle;
use yii\web\View;

class LazyLoadAsset extends AssetBundle
{

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        $this->js = [
            'jquery.lazyload.js',
        ];
        $this->depends = [
            'yii\web\JqueryAsset',
        ];
        if (file_exists(Yii::getAlias('@bower/jquery_lazyload'))) {
            $this->sourcePath = '@bower/jquery_lazyload';
        } else {
            $this->sourcePath = '@bower/jquery.lazyload';
        }
        $this->jsOptions = [
            'position' => View::POS_HEAD,
        ];
    }
}
