Widget Usage
---
### Property
* `model` Instance of Model|ActiveRecord. **Required** if `name` has not been defined.
* `attribute` attribute name in your model. **Required** if `name` has not been defined & when `model` has been defined.
* `name` input's name. **Required** if `model` & `attribute` has not been defined.
* `value` default value of tinymce. **Not required** Default is value of `$model->$attribute` or `empty` when not using `model` section
* `action` default is ` Url::to(['/roxymce/default/index'])` **Not required**
* `options` default options of textarea. **Not required**. See: [Html::textarea()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#textarea()-detail)
* `clientOptions` Tinymce options. **Not required**. See: https://www.tinymce.com/docs/
  * `title` Title bar of roxymce iframe. **Not required**

### Example
In your view file, call roxymce widget
#### Include ActiveRecord Model
~~~
[php]
echo \navatech\roxymce\widgets\RoxyMceWidget::widget([
	'model'     => app\models\Post::findOne(1),
	'attribute' => 'content',
]);
~~~
#### Sample HTML without ActiveRecord Model
~~~
[php]
echo \navatech\roxymce\widgets\RoxyMceWidget::widget([
	'name' => 'Post[content]'
]);
~~~