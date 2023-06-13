<?php

use app\models\StaticPage;
// О магазине 17
// контакты 18
$count = 0;
?>
<div class="footer_menu">

    <div class="row">

        <? if(isset(\Yii::$app->params['bottomPagesPid'][0])):?>
            <? foreach(Yii::$app->params['bottomPagesPid'][0] as $k=>$v):?>
            <?php if($v->id == 17 or $v->id == 18) continue ?>
                <div class="col">
                    <div class="footer-nav">
                        <span class="footer_menu-title"><a href="<?=\Yii::$app->functions->hierarchyUrl($v);?>"><?=Yii::$app->langs->t($v->name);?></a></span>
                        <? if(isset(\Yii::$app->params['bottomPagesPid'][$v->id])):?>
                            <ul>
                                <? foreach(Yii::$app->params['bottomPagesPid'][$v->id] as $kk=>$vv):?>
                                    <?php if($vv->id == 17 or $vv->id == 18) continue ?>
                                    <li><a href="<?=\Yii::$app->functions->hierarchyUrl($vv);?>"><?=Yii::$app->langs->t($vv->name);?></a></li>
                                <? endforeach;?>
                            </ul>
                        <? endif;?>
                    </div>
                    <?php if($count == 0) : ?>
                    <div class="social_icons">
                        <?=Yii::$app->params["settings"][9];?>
                    </div>
                    <?php endif; ?>
                </div>
            <? $count++; endforeach;?>
        <? endif;?>

        <div class="col">
            <span class="footer_menu-title"><?=Yii::$app->langs->t("Контакты");?></span>

            <ul>
                <?php if($page = StaticPage::findOne(17)) : ?>
                <li>
                    <a href="<?=\Yii::$app->functions->hierarchyUrl($page);?>">
                        <?=$page->name?>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($page = StaticPage::findOne(18)) : ?>
                    <li>
                        <a href="<?=\Yii::$app->functions->hierarchyUrl($page);?>">
                            <?=$page->name?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="col" style="width: 40%;">
            <?= $this->render('_feedback_form') ?>

        </div>


    </div>
</div>
<!--
<a href="https://ru-ru.facebook.com/maniamodeler/"><i class="facebook_icon"></i></a>
                <a href="https://www.instagram.com/maniamodeler/"><i class="instagram_icon"></i></a>
<a href="https://wa.me/+79999909109">
                    <i class="fa fa-whatsapp"></i>
                </a>
-->
