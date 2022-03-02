<?php
/**
 * Created by Navatech.
 * @project RoxyMce
 * @author  Le Phuong
 * @email   phuong17889[at]gmail.com
 * @date    28/01/2016
 * @time    1:59 SA
 * @version 2.0.0
 */

namespace navatech\roxymce\widgets;

use navatech\roxymce\assets\TinyMceAsset;
use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

/**
 * This is RoxyMce widget, call <?=RoxyMceWidget::widget([])?>
 * {@inheritDoc}
 */
class RoxyMceWidget extends Widget {

	/**
	 * @var ActiveRecord
	 */
	public $model;

	/**
	 * @var string attribute name
	 */
	public $attribute;

	/**
	 * @var string field's name (not required)
	 */
	public $name = 'content';

	/**
	 * @var string default value (not required)
	 */
	public $value;

	/**
	 * @var array RoxyMce options
	 * @see https://github.com/navatech/yii2-roxymce/blob/master/docs/widget.md
	 */
	public $options;

	/**
	 * @var array TinyMce options
	 * @see https://www.tinymce.com/docs/
	 */
	public $clientOptions = [];

	/**
	 * @var string default action of roxymce iframe, change it if you want customize this action
	 */
	public $action;

	/**
	 * @var string function callback of setup.
	 * @see   https://www.tiny.cloud/docs-4x/demo/custom-toolbar-menu-button/
	 */
	public $setup = '{}';

	/**
	 * @var array function callback of setup.
	 * @see     https://www.tiny.cloud/docs-4x/demo/custom-toolbar-menu-button/
	 * @example menu: {    custom: { title: 'Custom Menu', items: 'undo redo myCustomMenuItem' }}
	 */
	public $menu = null;

	/**
	 * @var array function callback of setup.
	 * @see     https://www.tiny.cloud/docs-4x/demo/custom-toolbar-menu-button/
	 * @example menu: 'mybutton'
	 */
	public $toolbar1 = null;

	/**
	 * @var array function callback of setup.
	 * @see     https://www.tiny.cloud/docs-4x/demo/custom-toolbar-menu-button/
	 * @example menu: 'mybutton'
	 */
	public $toolbar2 = null;

	/**
	 * @var array function callback of setup.
	 * @see     https://www.tiny.cloud/docs-4x/demo/custom-toolbar-menu-button/
	 * @example menu: 'mybutton'
	 */
	public $toolbar3 = null;

	/**
	 * Initializes the object.
	 * This method is invoked at the end of the constructor after the object is initialized with the
	 * given configuration.
	 * @throws InvalidParamException
	 */
	public function init() {
		parent::init();
		TinyMceAsset::register($this->view);
		if ($this->id === null) {
			if ($this->model !== null) {
				if ($this->attribute === null) {
					throw new InvalidParamException('Field "attribute" is required');
				} else {
					$model = $this->model;
					if (method_exists($model, 'hasAttribute') && $model->hasAttribute($this->attribute)) {
						$classNames = explode("\\", $model::className());
						$this->id   = end($classNames) . '_' . $this->attribute;
					} else {
						throw new InvalidParamException('Column "' . $this->attribute . '" not found in model');
					}
				}
			} else {
				if ($this->name === null) {
					throw new InvalidParamException('Field "name" is required');
				} else {
					$this->id = $this->name;
				}
			}
		}
		$this->options['id'] = $this->id;
		$this->clientOptions = ArrayHelper::merge($this->clientOptions, [
			'selector'     => '#' . $this->id,
			'plugins'      => [
				'advlist autolink autosave autoresize link image lists charmap print preview hr anchor pagebreak spellchecker',
				'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
				'table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern',
			],
			'theme'        => 'modern',
			'toolbar1'     => 'newdocument fullpage | undo redo | styleselect formatselect fontselect fontsizeselect',
			'toolbar2'     => 'print preview media | forecolor backcolor emoticons | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media code',
			'image_advtab' => true,
		]);
		if ($this->menu != null) {
			$menu_bar = [];
			foreach ($this->menu as $menu_name => $menu_config) {
				$menu_bar[] = $menu_name;
				if (isset($menu_config['items'])) {
					$menu_config['items'] = implode(' ', $menu_config['items']);
				} else {
					$menu_config['items'] = '';
				}
				$this->clientOptions['menu'][$menu_name] = $menu_config;
			}
			$this->clientOptions['menubar'] = implode(' ', $menu_bar);
		}
		if ($this->toolbar1 != null) {
			$this->clientOptions['toolbar1'] .= ' | ' . trim(implode(' ', $this->toolbar1));
		}
		if ($this->toolbar2 != null) {
			$this->clientOptions['toolbar2'] .= ' | ' . trim(implode(' ', $this->toolbar2));
		}
		if ($this->toolbar3 != null) {
			$this->clientOptions['toolbar3'] = implode(' ', $this->toolbar3);
		}
		if ($this->action === null) {
			$this->action = Url::to(['roxymce/default']);
		}
	}

	/**
	 * Executes the widget.
	 * @return string the result of widget execution to be outputted.
	 * @throws InvalidParamException
	 */
	public function run() {
		$this->view->registerJs('$(function() {
			tinyMCE.init({' . substr(Json::encode($this->clientOptions), 1, - 1) . ', "setup": ' . $this->setup . ', "file_browser_callback": RoxyFileBrowser});
		});', View::POS_HEAD);
		$this->view->registerJs('function RoxyFileBrowser(field_name, url, type, win) {
			var roxyMce = "' . $this->action . '";
			if(roxyMce.indexOf("?") < 0) {
				roxyMce += "?type=" + type;
			}
			else {
				roxyMce += "&type=" + type;
			}
			roxyMce += "&input=" + field_name + "&value=" + win.document.getElementById(field_name).value;
			if(tinyMCE.activeEditor.settings.language) {
				roxyMce += "&langCode=" + tinyMCE.activeEditor.settings.language;
			}
			tinyMCE.activeEditor.windowManager.open({
				file          : roxyMce,
				title         : "' . (array_key_exists('title', $this->clientOptions) ? $this->clientOptions['title'] : 'RoxyMce') . '",
				width         : 850,
				height        : 480,
				resizable     : "yes",
				plugins       : "media",
				inline        : "yes",
				close_previous: "no"
			}, {
				window: win,
				input : field_name
			});
			return false;
		}', View::POS_HEAD);
		if ($this->model !== null) {
			return Html::activeTextarea($this->model, $this->attribute, $this->options);
		} else {
			return Html::textarea($this->name, $this->value, $this->options);
		}
	}
}
