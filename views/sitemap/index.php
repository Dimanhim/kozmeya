<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
?>

<div class="breadcrumbs-wrap">
    <div class="container">
        <?= $this->render( '/parts/bcrumbs', [] ); ?>
    </div>
</div>


<main class="main">
    <div class="container">

    <h1><?=Html::encode(\Yii::$app->meta->getPageTitle(\Yii::$app->langs->t("Карта сайта")));?></h1>

    <div class="text">
        <?
        $chunks = array_chunk($result, ceil(count($result)/5));
        ?>


        <div style="font-size: 12px;" class="map-site">
            <? foreach($chunks as $chunk):?>
                <ul style="width: 240px; float: left;">
                    <? if (isset($chunk)): ?>
                        <? foreach ($chunk as $k=>$v): ?>
                            <li style="padding:7px;"><a href="<?=$v['link']?>"><?=$v['name']?></a></li>
                        <? endforeach; ?>
                    <? endif; ?>
                </ul>
            <? endforeach;?>
            <div style="clear:both;"></div>
        </div>
    </div>


	<?=\Yii::$app->meta->getSeoText();?>
    </div>
</main>
