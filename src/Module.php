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

use Yii;
use yii\base\InvalidParamException;

/**
 * @property array $config List of configure for Roxy Fileman
 * {@inheritDoc}
 */
class Module extends \navatech\base\Module {

	const VERSION = '2.0.0';

	public $uploadFolder       = '@app/web/uploads/images';

	public $defaultView        = 'thumb';

	public $dateFormat         = 'dd/MM/yyyy HH:mm';

	public $rememberLastAction = true;

	public $fileOptions        = [];

	public $imageOptions       = [];

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
		$defaultImageOptions = [
			'maxImageWidth'     => 1000,
			'maxImageHeight'    => 1000,
			'thumbViewWidth'    => 100,
			'thumbViewHeight'   => 100,
			'previewViewWidth'  => 100,
			'previewViewHeight' => 100,
		];
		$defaultFileOptions  = [
			'forbidden' => 'zip js jsp jsb mhtml mht xhtml xht php phtml php3 php4 php5 phps shtml jhtml pl sh py cgi exe application gadget hta cpl msc jar vb jse ws wsf wsc wsh ps1 ps2 psc1 psc2 msh msh1 msh2 inf reg scf msp scr dll msi vbs bat com pif cmd vxd cpl htpasswd htaccess',
			'allowed'   => 'jpeg jpg png gif mov mp3 mp4 avi wmv flv mpeg webm',
		];
		foreach ($defaultImageOptions as $defaultImageOptionKey => $defaultImageOption) {
			if (!isset($this->imageOptions[$defaultImageOptionKey])) {
				$this->imageOptions[$defaultImageOptionKey] = $defaultImageOption;
			}
		}
		foreach ($defaultFileOptions as $defaultFileOptionKey => $defaultFileOption) {
			if (!isset($this->fileOptions[$defaultFileOptionKey])) {
				$this->fileOptions[$defaultFileOptionKey] = $defaultFileOption;
			}
		}
		if (!is_dir(Yii::getAlias($this->uploadFolder))) {
			mkdir(Yii::getAlias($this->uploadFolder), 0777, true);
		}
	}
}
