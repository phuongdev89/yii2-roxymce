Module configure
---
### Property

* `uploadFolder` the directory where stored file. Default is `@app/web/uploads/images`
* `defaultView` display type. Default is `thumb`
* `dateFormat` Datetime format. Default is `Y-m-d H:i`. See: http://php.net/manual/en/function.date.php
* `rememberLastFolder` would you want to remember last folder? Default is `true`
* `allowExtension` allowed files extension. Default is `jpeg jpg png gif mov mp3 mp4 avi wmv flv mpeg webm`

### Example
Add to config file:
```
	'modules'    => [
		'roxymce'     => [
			'class' => 'navatech\roxymce\Module',
		],
	],
```


	/**
	 * @var string default folder which will be used to upload resource
	 *             must be start with @
	 */
	public $uploadFolder = '@app/web/uploads/images';

	/**
	 * @var string default view type
	 */
	public $defaultView = 'thumb';

	/**
	 * @var string default display dateFormat
	 * @see http://php.net/manual/en/function.date.php
	 */
	public $dateFormat = 'Y-m-d H:i';

	/**
	 * @var bool would you want to remember last folder?
	 */
	public $rememberLastFolder = true;

	/**
	 * @var string default allowed files extension
	 */
	public $allowExtension = 'jpeg jpg png gif mov mp3 mp4 avi wmv flv mpeg webm';
