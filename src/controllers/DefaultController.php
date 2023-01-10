<?php
/**
 * Created by phuongdev89.
 * @project roxymce
 * @author  Phuong Dev
 * @email   phuongdev89@gmail.com
 * @date    15/02/2016
 * @time    4:19 CH
 * @version 2.0.0
 */

namespace phuongdev89\roxymce\controllers;

use phuongdev89\roxymce\helpers\FolderHelper;
use phuongdev89\roxymce\models\UploadForm;
use phuongdev89\roxymce\Module;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * {@inheritDoc}
 */
class DefaultController extends Controller
{

    /**
     * Render a view
     *
     * @param $type
     *
     * @return string
     */
    public function actionIndex($type)
    {
        /**@var Module $module */
        $module = Yii::$app->getModule('roxymce');
        $uploadForm = new UploadForm();
        $defaultFolder = '';
        $defaultOrder = FolderHelper::SORT_DATE_DESC;
        Yii::$app->cache->set('roxy_file_type', $type);
        if ($module->rememberLastFolder && Yii::$app->cache->exists('roxy_last_folder')) {
            $defaultFolder = Yii::$app->cache->get('roxy_last_folder');
        }
        if ($module->rememberLastOrder && Yii::$app->cache->exists('roxy_last_order')) {
            $defaultOrder = Yii::$app->cache->get('roxy_last_order');
        }
        $fileListUrl = Url::to([
            '/roxymce/management/file-list',
            'folder' => $defaultFolder,
            'sort' => $defaultOrder,
        ]);
        return $this->renderAjax('index', [
            'module' => $module,
            'uploadForm' => $uploadForm,
            'fileListUrl' => $fileListUrl,
            'defaultOrder' => $defaultOrder,
            'defaultFolder' => $defaultFolder,
        ]);
    }
}
