<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
	'language' => 'ru-RU',
	'aliases' => [
        '@admin' => '@app/modules/admin',
    ],
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'app\components\Bootstrap', 'assetsAutoCompress'],
    'components' => [
        'amocrm' => [
            'class' => 'yii\amocrm\Client',
            'subdomain' => 'new593e8aacdc96f', // Персональный поддомен на сайте amoCRM
            'login' => 'business@maniamodeler.com', // Логин на сайте amoCRM
            'hash' => 'fbacc42fd704eb5993dd747336715615', // Хеш на сайте amoCRM
        ],
        'assetsAutoCompress' =>
        [
            'class'                         => '\skeeks\yii2\assetsAuto\AssetsAutoCompressComponent',
            'enabled'                       => false,

            'readFileTimeout'               => 3,           //Time in seconds for reading each asset file

            'jsCompress'                    => false,        //Enable minification js in html code
            'jsCompressFlaggedComments'     => false,        //Cut comments during processing js

            'cssCompress'                   => false,        //Enable minification css in html code

            'cssFileCompile'                => false,        //Turning association css files
            'cssFileRemouteCompile'         => false,       //Trying to get css files to which the specified path as the remote file, skchat him to her.
            'cssFileCompress'               => false,        //Enable compression and processing before being stored in the css file
            'cssFileBottom'                 => false,       //Moving down the page css files
            'cssFileBottomLoadOnJs'         => false,       //Transfer css file down the page and uploading them using js

            'jsFileCompile'                 => false,        //Turning association js files
            'jsFileRemouteCompile'          => false,       //Trying to get a js files to which the specified path as the remote file, skchat him to her.
            'jsFileCompress'                => false,        //Enable compression and processing js before saving a file
            'jsFileCompressFlaggedComments' => false,        //Cut comments during processing js

            'htmlCompress'                  => false,        //Enable compression html
            'noIncludeJsFilesOnPjax'        => false,        //Do not connect the js files when all pjax requests
            'htmlCompressOptions'           =>              //options for compressing output result
            [
                'extra' => false,        //use more compact algorithm
                'no-comments' => false   //cut all the html comments
            ],

        ],
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
            'idParam' => 'user',
        ],
        'siteuser' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-site', 'httpOnly' => true],
            'loginUrl'=>['/profile/login'],
            'idParam' => 'siteuser',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'session-frontend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //'useFileTransport' => true,

            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                //'username' => 'no-reply@kozmeya.com',
                //'password' => 'lB8wI5fF8axZ3yB2',
                'username' => 'request@kozmeya.com',
                'password' => 'hrf1dwjnAnf4',
                'port' => '465',
                'encryption' => '',
            ],
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
                '/' => 'site/index',

				'/admin' => 'admin/index',
                '/admin/login' => 'admin/index/login',
                '/admin/logout' => 'admin/index/logout',
                '/admin/<controller>' => 'admin/<controller>',
                '/admin/<controller>/<action>' => 'admin/<controller>/<action>',

                '/cart' => 'catalog/cart',
                '/compare' => 'catalog/cart',
                '/favorites' => 'catalog/favorites',
                '/karta-sayta' => 'sitemap/index',
                '/kassa' => 'kassa/index',

                '/profile' => 'profile/index',
                '/profile/<action>' => 'profile/<action>',
                '/profile/eauth/<service:google|facebook|vkontakte|odnoklassniki>' => 'profile/eauth',
			]
		],
        'eauth' => [
            'class' => 'nodge\eauth\EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'httpClient' => [
                // uncomment this to use streams in safe_mode
                //'useStreamsFallback' => true,
            ],
            'services' => [ // You can change the providers and their classes.
                'google' => [
                    // register your app here: https://code.google.com/apis/console/
                    'class' => 'nodge\eauth\services\GoogleOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                    'title' => 'Google',
                ],
                'twitter' => [
                    // register your app here: https://dev.twitter.com/apps/new
                    'class' => 'nodge\eauth\services\TwitterOAuth1Service',
                    'key' => '...',
                    'secret' => '...',
                ],
                'yandex' => [
                    // register your app here: https://oauth.yandex.ru/client/my
                    'class' => 'nodge\eauth\services\YandexOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                    'title' => 'Yandex',
                ],
                'facebook' => [
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'nodge\eauth\services\FacebookOAuth2Service',
                    'clientId' => '1085461338252983',
                    'clientSecret' => '0f72524a39dfda4510384a3b98e4145f',
                ],
                'yahoo' => [
                    'class' => 'nodge\eauth\services\YahooOpenIDService',
                    //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
                ],
                'linkedin' => [
                    // register your app here: https://www.linkedin.com/secure/developer
                    'class' => 'nodge\eauth\services\LinkedinOAuth1Service',
                    'key' => '...',
                    'secret' => '...',
                    'title' => 'LinkedIn (OAuth1)',
                ],
                'linkedin_oauth2' => [
                    // register your app here: https://www.linkedin.com/secure/developer
                    'class' => 'nodge\eauth\services\LinkedinOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                    'title' => 'LinkedIn (OAuth2)',
                ],
                'github' => [
                    // register your app here: https://github.com/settings/applications
                    'class' => 'nodge\eauth\services\GitHubOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                ],
                'live' => [
                    // register your app here: https://account.live.com/developers/applications/index
                    'class' => 'nodge\eauth\services\LiveOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                ],
                'steam' => [
                    'class' => 'nodge\eauth\services\SteamOpenIDService',
                    //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
                    'apiKey' => '...', // Optional. You can get it here: https://steamcommunity.com/dev/apikey
                ],
                'instagram' => [
                    // register your app here: https://instagram.com/developer/register/
                    'class' => 'nodge\eauth\services\InstagramOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                ],
                'vkontakte' => [
                    // register your app here: https://vk.com/editapp?act=create&site=1
                    'class' => 'nodge\eauth\services\VKontakteOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                ],
                'mailru' => [
                    // register your app here: http://api.mail.ru/sites/my/add
                    'class' => 'nodge\eauth\services\MailruOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                ],
                'odnoklassniki' => [
                    // register your app here: http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=13992188
                    // ... or here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
                    'class' => 'nodge\eauth\services\OdnoklassnikiOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                    'clientPublic' => '...',
                    'title' => 'Odnoklas.',
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'eauth' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@eauth/messages',
                ],
            ],
        ],
		'functions' => [
           'class' => 'app\components\Functions',
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
        $config['modules']['debug'] = ['class' => \yii\debug\Module::className(),'allowedIPs' => ['93.182.24.46']];
    }


    $config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = ['class' => \yii\gii\Module::className(),'allowedIPs' => ['93.182.24.46']];
}
return $config;
