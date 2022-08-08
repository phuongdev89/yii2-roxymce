<?php
/**
 * Created by Navatech.
 * @project roxymce
 * @author  Le Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    4:19 CH
 * @version 2.0.0
 * 
 * @author Ján Janki Úskoba <jan.uskoba[at]gmail.com>
 */
namespace janki1\roxymce\controllers;

use janki1\roxymce\helpers\FolderHelper;
use janki1\roxymce\models\UploadForm;
use janki1\roxymce\Module;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * {@inheritDoc}
 */
class DefaultController extends Controller {

    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'roles' => $this->module->role
                    ]
                ]
            ]
        ]);
    }
    
    /**
     * Render a view
     *
     * @param $type
     *
     * @return string
     */
    public function actionIndex($type = '') {
        /**@var Module $module */
        $uploadForm    = new UploadForm();
        $defaultFolder = '';
        $defaultOrder  = FolderHelper::SORT_DATE_DESC;
        Yii::$app->cache->set('roxy_file_type', $type);
        if ($this->module->rememberLastFolder && Yii::$app->cache->exists('roxy_last_folder')) {
            $defaultFolder = Yii::$app->cache->get('roxy_last_folder');
        }
        if ($this->module->rememberLastOrder && Yii::$app->cache->exists('roxy_last_order')) {
            $defaultOrder = Yii::$app->cache->get('roxy_last_order');
        }
        $fileListUrl = Url::to([
            'management/file-list',
            'folder' => $defaultFolder,
            'sort'   => $defaultOrder,
        ]);
        return $this->render('index', [
            'module'        => $this->module,
            'uploadForm'    => $uploadForm,
            'fileListUrl'   => $fileListUrl,
            'defaultOrder'  => $defaultOrder,
            'defaultFolder' => $defaultFolder,
        ]);
    }
}