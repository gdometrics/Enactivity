<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is some common Web application configurations. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Poncla',

	// preloading 'log' component
	'preload'=>array('log', 'timezonekeeper'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.helpers.*',
		'application.extensions.*',
	),

	// application components
	'components'=>array(
	
		'authManager'=>array(
            'class' => 'CPhpAuthManager',
        ),
        
		'mail' => array(
				'class' => 'ext.YiiMail',
				'transportType' => 'php',
				'viewPath' => 'application.views.mail',
				'logging' => false,
				'dryRun' => true,
		),
	
		'mailer'=>array(
        		'class'=>'application.extensions.mailer.Mailer',
        		'mailTransferAgent'=>'php',
				'shouldEmail'=>false,  
		),
	
		'timezonekeeper' => array(
			'class' => 'TimeZoneKeeper'
		),
	),
);