<?php
/**
 * Created by Navatech.
 * @project roxymce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    10:24 SA
 * @version 2.0.0
 */

namespace navatech\roxymce\assets;

use Yii;
use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * {@inheritDoc}
 */
class RoxyMceAsset extends AssetBundle {

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		parent::init();
		$this->sourcePath = '@vendor/navatech/yii2-roxymce/src/web';
		$this->depends    = [
			'yii\web\JqueryAsset',
			'yii\bootstrap\BootstrapAsset',
			'yii\bootstrap\BootstrapPluginAsset',
			'navatech\roxymce\assets\FontAwesomeAsset',
			'navatech\roxymce\assets\BootstrapTreeviewAsset',
			'navatech\roxymce\assets\LazyLoadAsset',
			'navatech\roxymce\assets\FancyBoxAsset',
			'navatech\roxymce\assets\ContextMenuAsset',
		];
		$this->css        = [
			YII_ENV_DEV ? 'css/roxy.css' : 'css/roxy.min.css',
		];
		$this->js         = [
			YII_ENV_DEV ? 'js/roxy.js' : 'js/roxy.min.js',
		];
		Yii::$app->view->registerJs('var msg_somethings_went_wrong = "' . Yii::t('roxy', 'Somethings went wrong') . '",
msg_empty_directory = "' . Yii::t('roxy', 'Empty directory') . '",
msg_please_select_one_folder = "' . Yii::t('roxy', 'Please select one folder') . '",
msg_are_you_sure = "' . Yii::t('roxy', 'Are you sure?') . '",
msg_preview = "' . Yii::t('roxy', 'Preview') . '",
msg_download = "' . Yii::t('roxy', 'Download') . '",
msg_cut = "' . Yii::t('roxy', 'Cut') . '",
msg_copy = "' . Yii::t('roxy', 'Copy') . '",
msg_paste = "' . Yii::t('roxy', 'Paste') . '",
msg_rename = "' . Yii::t('roxy', 'Rename') . '",
msg_delete = "' . Yii::t('roxy', 'Delete') . '",
url_folder_remove = "' . Url::to(['/roxymce/management/folder-remove']) . '",
url_file_upload = "' . Url::to(['/roxymce/management/file-upload']) . '",
url_file_cut = "' . Url::to(['/roxymce/management/file-cut']) . '",
url_file_copy = "' . Url::to(['/roxymce/management/file-copy']) . '",
url_file_paste = "' . Url::to(['/roxymce/management/file-paste']) . '",
url_file_remove = "' . Url::to(['/roxymce/management/file-remove']) . '";
		', View::POS_HEAD);
	}
}