<? $prices = \Yii::$app->catalog->orderPrice($model);?>
<div>
    <div class="row">
        <div class="col-xs-6">Итого:</div>
        <div class="col-xs-6"><?=\Yii::$app->functions->getPrice($prices["result_price"]);?> руб.</div>
    </div>
    <? if(isset($br) && $br):?><br><? endif;?>

    <? if($model->adding_price > 0):?>
        <div class="row">
            <div class="col-xs-6">Добавочная стоимость:</div>
            <div class="col-xs-6"><?=\Yii::$app->functions->getPrice($model->adding_price);?> руб.</div>
        </div>
        <? if(isset($br) && $br):?><br><? endif;?>
    <? endif;?>

    <? if($model->delivery_price > 0):?>
        <div class="row">
            <div class="col-xs-6">Доставка:</div>
            <div class="col-xs-6"><?=\Yii::$app->functions->getPrice($model->delivery_price);?> руб.</div>
        </div>
        <? if(isset($br) && $br):?><br><? endif;?>
    <? endif;?>

    <? if($model->discount_value > 0):?>
        <div class="row">
            <div class="col-xs-6">Скидка:</div>
            <div class="col-xs-6"><?=$model->discount_value;?> <?=($model->discount_type == 1 ? "%" : "р.");?></div>
        </div>
        <? if(isset($br) && $br):?><br><? endif;?>
    <? endif;?>

    <? if($model->promocode != ""):?>
        <div class="row">
            <div class="col-xs-6">Промо-код:</div>
            <div class="col-xs-6"><?=$model->promocode;?></div>
        </div>
        <? if(isset($br) && $br):?><br><? endif;?>
    <? endif;?>

    <hr>
    <div class="row">
        <div class="col-xs-6"><b>Итого с доставкой:</b></div>
        <div class="col-xs-6"><?=\Yii::$app->functions->getPrice($prices["result_price"]+$model->delivery_price+$model->adding_price);?> руб.</div>
    </div>
</div>
