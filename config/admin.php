<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'admin',
	'language' => 'ru-RU',
	'aliases' => [
        '@admin' => '@app/modules/admin',
    ],
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Uy72R9cCHeFHlPS9ZdMWfPzwkU4WIawl',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\AdminUsers',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-admin', 'httpOnly' => true],
            'loginUrl'=>['/admin/login'],
        ],
        'siteuser' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-site', 'httpOnly' => true],
            'loginUrl'=>['/profile/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'session-frontend',
        ],
        'errorHandler' => [
            'errorAction' => 'admin/index/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //'useFileTransport' => true,
            /*
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => '',
                'username' => '',
                'password' => '',
                'port' => '',
                'encryption' => '',
            ],
            */

        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
		'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
			'rules' => [
				'/admin' => 'admin/index',
                '/admin/login' => 'admin/index/login',
                '/admin/logout' => 'admin/index/logout',
                '/admin/<controller>' => 'admin/<controller>',
                '/admin/<controller>/<action>' => 'admin/<controller>/<action>',
                '/<controller>/<action>' => '<controller>/<action>',
			]
		],
		'functions' => [
           'class' => 'app\components\Functions',
		],
        'adminfunctions' => [
                'class' => 'app\modules\admin\components\Functions',
        ],
        'socials' => [
                'class' => 'app\modules\admin\components\Socials',
        ],
        'meta' => [
            'class' => 'app\components\Meta',
        ],
        'catalog' => [
            'class' => 'app\components\Catalog',
        ],
        'kassa' => [
            'class' => 'app\components\Kassa',
        ],
        'sms' => [
            'class' => 'app\modules\admin\components\Sms',
        ],
        'langs' => [
            'class' => 'app\components\Langs',
        ],
    ],
    'params' => $params,
	'modules' => [
		'admin' => [
			'layout' => 'main',
			'class' => 'app\modules\admin\Admin',
		],
	],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    if(YII_DEBUG){
        $config['bootstrap'][] = 'debug';
        $config['modules']['debug'] = ['class' => \yii\debug\Module::className(),'allowedIPs' => ['*']];
    }

	
    $config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = ['class' => \yii\gii\Module::className(),'allowedIPs' => ['*']];
}
return $config;
