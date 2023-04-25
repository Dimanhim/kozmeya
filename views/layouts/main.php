<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
\Yii::$app->params['frontPage'] = (Yii::$app->controller->id == "site" && Yii::$app->controller->action->id == "index" ? true : false);
\Yii::$app->params['errorPage'] = (Yii::$app->controller->action->id == "error" ? true : false);
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE = edge">


    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="shortcut icon" href="/mania-favicon-16.png">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="/mania-favicon-128.png">

    <?php $this->head() ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <? $canonicalUrl = trim(Url::base(true)."/".Yii::$app->params["nonGetUrl"]);?>
    <?=$this->registerLinkTag(['rel' => 'canonical', 'href' => $canonicalUrl]);?>

    <? if(isset($_SERVER["REDIRECT_QUERY_STRING"]) && $_SERVER["REDIRECT_QUERY_STRING"] != ""):?>
        <meta name="robots" content="noindex,follow">
    <? else:?>
        <meta name="robots" content="index,follow">
    <? endif;?>

    <? /* SEO META */?>
    <?=Yii::$app->params["settings"][3];?>
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/jquery.formstyler.css">
    <!-- <link rel="stylesheet" type="text/css" href="/css/jquery.formstyler.theme.css"> -->

    <script type="text/javascript" src="/js/popper.js"></script>
</head>
<? \Yii::$app->params['bodyClass'] = "";?>
<body class="<?=(\Yii::$app->params['errorPage'] ? "" : (\Yii::$app->params['frontPage'] ? "" : \Yii::$app->params['bodyClass']));?>">
    <div class="box-overlay" onclick="void(0)"></div>
    <div class="">
    <?php $this->beginBody() ?>

    <? if(!Yii::$app->user->isGuest):?>
        <?//= $this->render('/parts/dashboard', [] ); ?>
    <? endif;?>

    <?= $this->render('/parts/header', [] ); ?>

    <?= $content ?>

    <div class="container">
    <?= $this->render('/parts/footer', [] ); ?>
    </div>
    
    <?= $this->render('/parts/popups', [] ); ?>

    <?php $this->endBody() ?>
    </div>

    <? /* SEO SCRIPTS */?>

    
    <?=Yii::$app->params["settings"][4];?>
    
    <script type="text/javascript" src="/js/jquery.formstyler.min.js"></script>

    <script>
        var _lang = '<?=\Yii::$app->params['lang'];?>';
        var _t = [];
        <? if(isset(\Yii::$app->params['t'][\Yii::$app->params['lang']])) foreach (\Yii::$app->params['t'][\Yii::$app->params['lang']] as $value=>$translate):?>
            _t['<?=$value;?>'] = '<?=$translate;?>';
        <? endforeach;?>
    </script>
</body>
</html>
<?php $this->endPage() ?>