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
use yii\base\Module as ModuleBase;
use yii\helpers\Url;

/**
 * @property array $config List of configure for Roxy Fileman
 * {@inheritDoc}
 */
class Module extends ModuleBase {

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
			'ALLOWED_UPLOADS'      => 'jpg png jpeg gif mp4 mp3 avi wmv flv',
			'FILEPERMISSIONS'      => '0644',
			'DIRPERMISSIONS'       => '0755',
			'LANG'                 => Yii::$app->language,
			'DATEFORMAT'           => 'dd/MM/yyyy HH:mm',
			'OPEN_LAST_DIR'        => 'yes',
			'INTEGRATION'          => 'tinymce4',
			'DIRLIST'              => Url::to('manager/dirlist'),
			'CREATEDIR'            => Url::to('manager/createdir'),
			'DELETEDIR'            => Url::to('manager/deletedir'),
			'MOVEDIR'              => Url::to('manager/movedir'),
			'COPYDIR'              => Url::to('manager/copydir'),
			'RENAMEDIR'            => Url::to('manager/renamedir'),
			'FILESLIST'            => Url::to('manager/fileslist'),
			'UPLOAD'               => Url::to('manager/upload'),
			'DOWNLOAD'             => Url::to('manager/download'),
			'DOWNLOADDIR'          => Url::to('manager/downloaddir'),
			'DELETEFILE'           => Url::to('manager/deletefile'),
			'MOVEFILE'             => Url::to('manager/movefile'),
			'COPYFILE'             => Url::to('manager/copyfile'),
			'RENAMEFILE'           => Url::to('manager/renamefile'),
			'GENERATETHUMB'        => Url::to('manager/generatethumb'),
		];
		foreach ($config as $key => $value) {
			if (!array_key_exists($key, $this->config)) {
				$this->config[$key] = $value;
			}
			define($key, $value);
		}
		$FilesRoot = RoxyHelper::fixPath(RoxyHelper::getFilesPath());
		if (!is_dir($FilesRoot)) {
			@mkdir($FilesRoot, octdec(DIRPERMISSIONS));
		}
	}
}