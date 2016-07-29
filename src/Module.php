<?php
/**
 * Created by Navatech.
 * @project RoxyMce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    4:33 CH
 * @version 1.0.0
 */
namespace navatech\roxymce;

use navatech\roxymce\helpers\RoxyHelper;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Url;

/**
 * @property array $config List of configure for Roxy Fileman
 * {@inheritDoc}
 */
class Module extends \navatech\base\Module {

	const VERSION = '1.0.0';

	public $config = [];

	/**
	 * Initializes the module.
	 *
	 * This method is called after the module is created and initialized with property values
	 * given in configuration. The default implementation will initialize [[controllerNamespace]]
	 * if it is not set.
	 *
	 * If you override this method, please make sure you call the parent implementation.
	 * @throws InvalidParamException
	 */
	public function init() {
		parent::init();
		$config = [
			'FILES_ROOT'           => 'uploads/image',
			'RETURN_URL_PREFIX'    => '',
			'SESSION_PATH_KEY'     => '',
			'THUMBS_VIEW_WIDTH'    => '100',
			'THUMBS_VIEW_HEIGHT'   => '100',
			'PREVIEW_THUMB_WIDTH'  => '100',
			'PREVIEW_THUMB_HEIGHT' => '100',
			'MAX_IMAGE_WIDTH'      => '1000',
			'MAX_IMAGE_HEIGHT'     => '1000',
			'DEFAULTVIEW'          => 'thumb',
			'FORBIDDEN_UPLOADS'    => 'zip js jsp jsb mhtml mht xhtml xht php phtml php3 php4 php5 phps shtml jhtml pl sh py cgi exe application gadget hta cpl msc jar vb jse ws wsf wsc wsh ps1 ps2 psc1 psc2 msh msh1 msh2 inf reg scf msp scr dll msi vbs bat com pif cmd vxd cpl htpasswd htaccess',
			'ALLOWED_UPLOADS'      => 'jpeg jpg png gif mov mp3 mp4 avi wmv flv mpeg',
			'FILEPERMISSIONS'      => '0644',
			'DIRPERMISSIONS'       => '0755',
			'LANG'                 => Yii::$app->language,
			'DATEFORMAT'           => 'dd/MM/yyyy HH:mm',
			'OPEN_LAST_DIR'        => 'yes',
			'INTEGRATION'          => 'tinymce4',
			'DIRLIST'              => Url::toRoute('/roxymce/manager/dirlist'),
			'CREATEDIR'            => Url::toRoute('/roxymce/manager/createdir'),
			'DELETEDIR'            => Url::toRoute('/roxymce/manager/deletedir'),
			'MOVEDIR'              => Url::toRoute('/roxymce/manager/movedir'),
			'COPYDIR'              => Url::toRoute('/roxymce/manager/copydir'),
			'RENAMEDIR'            => Url::toRoute('/roxymce/manager/renamedir'),
			'FILESLIST'            => Url::toRoute('/roxymce/manager/fileslist'),
			'UPLOAD'               => Url::toRoute('/roxymce/manager/upload'),
			'DOWNLOAD'             => Url::toRoute('/roxymce/manager/download'),
			'DOWNLOADDIR'          => Url::toRoute('/roxymce/manager/downloaddir'),
			'DELETEFILE'           => Url::toRoute('/roxymce/manager/deletefile'),
			'MOVEFILE'             => Url::toRoute('/roxymce/manager/movefile'),
			'COPYFILE'             => Url::toRoute('/roxymce/manager/copyfile'),
			'RENAMEFILE'           => Url::toRoute('/roxymce/manager/renamefile'),
			'GENERATETHUMB'        => Url::toRoute('/roxymce/manager/generatethumb'),
		];
		foreach ($config as $key => $value) {
			if (!array_key_exists($key, $this->config)) {
				$this->config[$key] = $value;
			}
		}
		foreach ($this->config as $key => $value) {
			if (!defined($key)) {
				define($key, $value);
			}
		}
		$FilesRoot = RoxyHelper::fixPath(RoxyHelper::getFilesPath());
		if (!is_dir($FilesRoot)) {
			@mkdir($FilesRoot, octdec(DIRPERMISSIONS), true);
		}
	}
}
