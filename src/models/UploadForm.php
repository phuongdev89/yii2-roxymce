<?php
/**
 * Created by Navatech.
 * @project roxymce
 * @author  Le Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    4:19 CH
 * @version 2.0.0
 */
namespace navatech\roxymce\models;

use navatech\roxymce\Module;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model {

	/**
	 * @var UploadedFile
	 */
	public $file;

	/**
	 * {@inheritDoc}
	 */
	public function rules() {
		/**@var Module $module */
		$module = \Yii::$app->getModule('roxymce');
		return [
			[
				['file'],
				'file',
				'skipOnEmpty' => true,
				'extensions'  => implode(',', explode(' ', $module->allowExtension)),
			],
		];
	}

	/**
	 * @param $folder
	 *
	 * @return bool
	 */
	public function upload($folder) {
		if ($this->validate()) {
			$filePath = $folder . DIRECTORY_SEPARATOR . $this->file->baseName . '.' . $this->file->extension;
			if (file_exists($filePath)) {
				$filePath = $folder . DIRECTORY_SEPARATOR . $this->file->baseName . '_' . time() . '.' . $this->file->extension;
			}
			$this->file->saveAs($filePath);
			return true;
		} else {
			return false;
		}
	}
}