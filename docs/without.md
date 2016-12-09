Without  TinyMCE Usage
---
In this case, you can use roxymce without TinyMCE intergrated. Just use with fancybox, iframe, bootstrap modal, colorbox or anythings popup/dialog you want.
### Property
* `type` type of displayed media. **Required**. Value:
  * `image`
  * `media`
* `dialog` type of dialog. **Required**. Supported:
  * `fancybox`
  * `modal`
  * `colorbox`
  * `iframe`
* `input` input's ID. **Required**

### Example
In your view file, call roxymce widget
~~~
[php]
	<?= $form->field($model, 'thumb')->textInput(['id' => 'fieldID'])->label(false) ?>
	<a href="<?= \yii\helpers\Url::to([
		'/roxymce/default',
		'type'   => 'image',
		'input'  => 'fieldID',
		'dialog' => 'fancybox',
	]) ?>"><i class="fa fa-upload"></i></a>
	<script>
		$("a").fancybox();
	</script>
~~~
