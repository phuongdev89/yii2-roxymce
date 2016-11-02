<?php
/**
 * Created by Navatech.
 * @project yii2-setting
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    05/07/2016
 * @time    11:50 PM
 * @version 2.0.0
 */
namespace navatech\roxymce;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;

class Bootstrap implements BootstrapInterface {

	/**
	 * Bootstrap method to be called during application bootstrap stage.
	 *
	 * @param Application $app the application currently running
	 */
	public function bootstrap($app) {
		if (!isset($app->get('i18n')->translations['roxy*'])) {
			$app->get('i18n')->translations['roxy*'] = [
				'class'          => PhpMessageSource::className(),
				'basePath'       => __DIR__ . '/messages',
				'sourceLanguage' => 'en-US',
			];
		}
		Yii::setAlias('roxymce', __DIR__);
	}
}