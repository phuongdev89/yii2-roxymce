<?php
/**
 * @project yii2-roxymce
 * @author  Le Phuong
 * @email phuong17889@gmail.com
 * @date    10/25/2016
 * @time    10:43 AM
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
				'extensions'  => implode(',', explode(' ', $module->fileOptions['allowed'])),
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