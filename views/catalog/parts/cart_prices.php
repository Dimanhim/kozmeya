<table class="table5">
    <thead>
    <tr>
        <td><?=Yii::$app->langs->t("Стоимость товаров");?>:</td>
        <td>
            <span><?=\Yii::$app->catalog->currencyPrice(new app\models\Items(), "price", "", "", \Yii::$app->params['cart']["items_price"]);?></span>
        </td>
    </tr>

    <? if(\Yii::$app->params['cart']["delivery_price"] > 0):?>
        <tr>
            <td><?=Yii::$app->langs->t("Стоимость доставки");?>:</td>
            <td>
                <span><?=\Yii::$app->catalog->currencyPrice(new app\models\Items(), "price", "", "", \Yii::$app->params['cart']["delivery_price"]);?></span>
            </td>
        </tr>
    <? endif;?>

    <? if(\Yii::$app->params['cart']["promocode"] != ""):?>
        <tr>
            <td><?=Yii::$app->langs->t("Промо-код");?>:</td>
            <td>
                <span><?=\Yii::$app->params['cart']["promocode"];?></span>
            </td>
        </tr>
    <? endif;?>
    <? if(\Yii::$app->params['cart']["discount_value"] > 0):?>
        <tr>
            <td><?=Yii::$app->langs->t("Скидка");?>:</td>
            <td>
                <span><?=\Yii::$app->params['cart']["discount_value"];?> <?=(\Yii::$app->params['cart']["discount_type"] == 1 ? "%" : \Yii::$app->params['langs_code'][\Yii::$app->params['lang']]->currency->symbol);?></span>
            </td>
        </tr>
    <? endif;?>
    <tr>
        <td><?=Yii::$app->langs->t("Итого");?>:</td>
        <td>
            <span class="summary"><?=\Yii::$app->catalog->currencyPrice(new app\models\Items(), "price", "", "", \Yii::$app->params['cart']["price"]+\Yii::$app->params['cart']["delivery_price"]);?></span>
        </td>
    </tr>
    </thead>
</table>