<?php
/**
 * Created by Navatech.
 * @project yii2-setting
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    05/07/2016
 * @time    11:50 PM
 */
namespace navatech\setting;

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
		if (!isset($app->get('i18n')->translations['roxymce*'])) {
			$app->get('i18n')->translations['roxymce*'] = [
				'class'          => PhpMessageSource::className(),
				'basePath'       => __DIR__ . '/messages',
				'sourceLanguage' => 'en-US',
			];
		}
	}
}