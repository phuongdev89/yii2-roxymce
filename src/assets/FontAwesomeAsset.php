<?php
/**
 * Created by phuongdev89.
 * @project roxymce
 * @author  Phuong Dev
 * @email   phuongdev89@gmail.com
 * @date    17/02/2016
 * @time    12:09 CH
 * @version 2.0.0
 */

namespace phuongdev89\roxymce\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * This will register asset for FontAwesome
 * {@inheritDoc}
 */
class FontAwesomeAsset extends AssetBundle
{

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        $this->depends = [
            'yii\web\JqueryAsset',
        ];
        if (file_exists(Yii::getAlias('@bower/font-awesome'))) {
            $this->sourcePath = '@bower/font-awesome';
        } else {
            $this->sourcePath = '@bower/fontawesome';
        }
        $this->css = [
            'css/font-awesome.min.css',
        ];
    }
}
