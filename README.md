# RoxyMce
This allow to intergrate TinyMce 4 with Roxy Fileman
TODO recode actions

## Usage
~~~
[php]
	'modules'    => [
		'roxymce'  => [
			'class' => '\navatech\roxymce\Module',
			'config'=> [
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