<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
?>

<div class="wrapper">
	
    <div class="header_top" <? if ($page->images != ''): ?>style="background-image: url(<?=Yii::$app->functions->getUploadItem( $page )?>); "<? endif; ?>>
        <span><?=Html::encode(\Yii::$app->meta->getPageTitle($page->name));?></span>
    </div>
</div>

<div class="content">

    <div class="static_text">
        <?=$page->text;?>

        <?=\Yii::$app->meta->getSeoText();?>
    </div>
</div>
