<?=\Yii::$app->catalog->currencyPrice($item, "price", "<span class='itemPrice'>$</span>");?>
<? if($item->old_price > 0):?>
<span class="old_price">
    <?=\Yii::$app->catalog->currencyPrice($item, "old_price", "<span class='itemOldPrice itemOldPriceLine'>$</span>");?> <span class="line1"></span>
</span>
<? endif;?>
