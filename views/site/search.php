<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
?>

<div class="wrapper">
    <div class="header_top" style="background-image: url(/img/header.png); ">
        <span>Поиск</span>
    </div>
</div>

<div class="content">

    <div class="static_text">
        <form action="/search">
            <input type="text" placeholder="Поиск" name="s" value="<?=(isset($_GET["s"]) ? $_GET["s"] : "");?>">
            <button type="submit">Найти</button>
        </form>

        <div><?= Yii::$app->langs->t("Найдено")?> <?=count($sections);?> <?=\Yii::$app->functions->plural(count($sections), Yii::$app->langs->t("результат"), Yii::$app->langs->t("результата"), Yii::$app->langs->t("результатов"));?> по запросу “<?=(isset($_GET["s"]) ? $_GET["s"] : "");?>”</div>


        <? if($sections) foreach($sections as $k=>$v):?>
            <div class="item">
                <a href="<?=$v["url"];?>" class="name"><?=$v["name"];?></a>
                <div class="text">
                    <? if(isset($v["image"]) && $v["image"] != ""):?><img src="<?=$v["image"];?>"><? endif;?>
                    <a href="<?=$v["url"];?>"><?= Yii::$app->langs->t("Перейти")?></a>
                </div>
            </div>
        <? endforeach;?>
    </div>
</div>

