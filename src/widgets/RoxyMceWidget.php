<?php
/**
 * Created by Navatech.
 * @project RoxyMce
 * @author  Phuong
 * @email   notteen[at]gmail.com
 * @date    28/01/2016
 * @time    1:59 SA
 * @version 1.0.0
 */
namespace navatech\roxymce\widgets;

use navatech\roxymce\TinyMceAsset;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

/**
 * This is RoxyMce widget, call <?=RoxyMceWidget::widget([])?>
 * {@inheritDoc}
 */
class RoxyMceWidget extends Widget {

	/**@var ActiveRecord */
	public $model;

	public $attribute;

	public $name;

	public $value;

	public $options;

	public $htmlOptions = [];

	public $action;

	/**
	 * Initializes the object.
	 * This method is invoked at the end of the constructor after the object is initialized with the
	 * given configuration.
	 * @throws InvalidParamException
	 */
	public function init() {
		parent::init();
		TinyMceAsset::register($this->view);
		if ($this->model !== null) {
			if ($this->attribute === null) {
				throw new InvalidParamException('Field "attribute" is required');
			} else {
				$model = $this->model;
				if ($model->hasAttribute($this->attribute)) {
					$this->id = $model::tableName() . '_' . $this->attribute;
				} else {
					throw new InvalidParamException('Column "' . $this->attribute . '" not found in ' . $model::tableName());
				}
			}
		} else {
			if ($this->name === null) {
				throw new InvalidParamException('Field "name" is required');
			} else {
				$this->id = $this->name;
			}
		}
		if (!array_key_exists('id', $this->htmlOptions)) {
			$this->htmlOptions['id'] = $this->id;
		}
		if ($this->options === null) {
			$this->options = [
				'selector'     => '#' . $this->id,
				'plugins'      => [
					'advlist autolink lists link image charmap print preview hr anchor pagebreak',
					'searchreplace wordcount visualblocks visualchars code fullscreen',
					'insertdatetime media nonbreaking save table contextmenu directionality',
					'emoticons template paste textcolor colorpicker textpattern imagetools',
				],
				'theme'        => 'modern',
				'toolbar1'     => 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
				'toolbar2'     => 'print preview media | forecolor backcolor emoticons',
				'image_advtab' => true,
			];
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
			tinyMCE.init({' . substr(Json::encode($this->options), 1, - 1) . ',"file_browser_callback": RoxyFileBrowser});
		});', View::POS_HEAD);
		$this->view->registerJs('function RoxyFileBrowser(field_name, url, type, win) {
			var roxyFileman = "' . $this->action . '";
			if(roxyFileman.indexOf("?") < 0) {
				roxyFileman += "?type=" + type;
			}
			else {
				roxyFileman += "&type=" + type;
			}
			roxyFileman += "&input=" + field_name + "&value=" + win.document.getElementById(field_name).value;
			if(tinyMCE.activeEditor.settings.language) {
				roxyFileman += "&langCode=" + tinyMCE.activeEditor.settings.language;
			}
			tinyMCE.activeEditor.windowManager.open({
				file          : roxyFileman,
				title         : "' . (array_key_exists('title', $this->options) ? $this->options['title'] : 'RoxyMce') . '",
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
			echo Html::activeTextarea($this->model, $this->attribute, $this->htmlOptions);
		} else {
			echo Html::textarea($this->name, $this->value, $this->htmlOptions);
		}
	}
}