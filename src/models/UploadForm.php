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

namespace phuongdev89\roxymce\models;

use phuongdev89\roxymce\helpers\FileHelper;
use phuongdev89\roxymce\Module;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{

    /**
     * @var UploadedFile[]
     */
    public $file;

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        /**@var Module $module */
        $module = Yii::$app->getModule('roxymce');
        return [
            [
                ['file'],
                'file',
                'skipOnEmpty' => true,
                'extensions' => implode(',', explode(' ', $module->allowExtension)),
                'maxFiles' => 20,
            ],
        ];
    }

    /**
     * @param $folder
     *
     * @return bool
     */
    public function upload($folder)
    {
        if ($this->validate()) {
            foreach ($this->file as $file) {
                $filePath = $folder . DIRECTORY_SEPARATOR . FileHelper::removeSign($file->baseName) . '.' . $file->extension;
                if (file_exists($filePath)) {
                    $filePath = $folder . DIRECTORY_SEPARATOR . FileHelper::removeSign($file->baseName) . '_' . time() . '.' . $file->extension;
                }
                $file->saveAs($filePath);
            }
            return true;
        } else {
            return false;
        }
    }
}
