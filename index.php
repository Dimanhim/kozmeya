<?php
error_reporting(E_WARNING);

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/config/web.php');
if(preg_match("/\/admin/", $_SERVER["REQUEST_URI"])) {
    $config = require(__DIR__ . '/config/admin.php');
}

(new yii\web\Application($config))->run();
