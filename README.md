# RoxyMce - Beautiful File manager for Tinymce
This allow to integrate [TinyMce](https://github.com/tinymce/tinymce) 4 with [Roxy Fileman](http://roxyfileman.com)

User story
---
I'm try to find a good file manager for tinymce for a long time.

elFinder is good, but too much function and it's not my style. MoxieManager maybe best with tinymce, but it's too much for me.

One day, I saw roxyman, immediately, I know it is all I need.
## Usage
~~~
[php]
	'modules'    => [
		'roxymce'  => [
			'class' => '\navatech\roxymce\Module',
			'config'=> [
			//all is not required
						'FILES_ROOT'           => 'uploads/image',
            			'RETURN_URL_PREFIX'    => '',
            			'SESSION_PATH_KEY'     => '',
            			'THUMBS_VIEW_WIDTH'    => '140',
            			'THUMBS_VIEW_HEIGHT'   => '120',
            			'PREVIEW_THUMB_WIDTH'  => '100',
            			'PREVIEW_THUMB_HEIGHT' => '100',
            			'MAX_IMAGE_WIDTH'      => '1000',
            			'MAX_IMAGE_HEIGHT'     => '1000',
            			'DEFAULTVIEW'          => 'list',
            			'FORBIDDEN_UPLOADS'    => 'zip js jsp jsb mhtml mht xhtml xht php phtml php3 php4 php5 phps shtml jhtml pl sh py cgi exe application gadget hta cpl msc jar vb jse ws wsf wsc wsh ps1 ps2 psc1 psc2 msh msh1 msh2 inf reg scf msp scr dll msi vbs bat com pif cmd vxd cpl htpasswd htaccess',
            			'ALLOWED_UPLOADS'      => '',
            			'FILEPERMISSIONS'      => '0644',
            			'DIRPERMISSIONS'       => '0755',
            			'LANG'                 => 'en',
            			'DATEFORMAT'           => 'dd/MM/yyyy HH:mm',
            			'OPEN_LAST_DIR'        => 'yes',
]
		],
	],

~~~
TODO need auto create folder for FILESROOT