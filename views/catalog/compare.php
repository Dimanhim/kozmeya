<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
?>

<main class="work__area">
    <div class="container">
        <header class="work__area-head">
            <?= $this->render( '/parts/bcrumbs', [] ); ?>

            <h1 class="h1-head"><?=Html::encode(\Yii::$app->meta->getPageTitle("Compare"));?></h1>
        </header>
        <div class="detail-shares-area">
            <? if(count(\Yii::$app->params["compares"]) > 0):?>
                <? if(count($props["props"]) > 0):?>
                    <table>
                        <tr>
                            <? foreach($props["props"] as $kk=>$vv):?>
                                <th><?=$vv->prop->name;?></th>
                            <? endforeach;?>
                        </tr>
                    </table>
                <? endif;?>

                <br>

                <? foreach(\Yii::$app->params["compares"] as $k=>$v):?>
                    <?= $this->render( '/catalog/parts/item', ['v' => $v, 'catalog' => true] ); ?>
                    <? if(count($props["props"]) > 0):?>
                        <table>
                            <tr>
                                <? foreach($props["props"] as $kk=>$vv):?>
                                    <td><?=(isset($props["values"][$v->id][$vv->id]) && $props["values"][$v->id][$vv->id]->value != "" ? $props["values"][$v->id][$vv->id]->value : "-");?></td>
                                <? endforeach;?>
                            </tr>
                        </table>
                    <? endif;?>
                <? endforeach;?>
            <? else:?>
                <?=Yii::$app->langs->t("В сравнении ничего нет")?>
            <? endif;?>

            <?=\Yii::$app->meta->getSeoText();?>
        </div>
    </div>
</main>
