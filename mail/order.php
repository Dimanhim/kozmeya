<? $prices = \Yii::$app->catalog->orderPrice($model);?>
<? if(isset($text) && $text != ""):?>
    <?=$text;?>
<? else:?>
    Здравствуйте, <?=$model->name;?>,<br>
    Вы подтвердили заказ на полную сумму <?=\Yii::$app->functions->getPrice($prices["result_price"]+$model->delivery_price+$model->adding_price);?> руб
    <br>Номер заказа: <?=$model->id;?><br>
    <p>&nbsp;</p>
<? endif;?>

<table width="100%" style="width:100%;">
    <tr>
        <td>Имя</td>
        <td><?=$model->name?></td>
    </tr>
    <tr>
        <td>E-mail</td>
        <td><?=$model->email?></td>
    </tr>
    <tr>
        <td>Телефон</td>
        <td><?=$model->phone?></td>
    </tr>

    <tr>
        <td>Информация о доставке</td>
        <td>
            <div>Тип доставки: <?=$model->delivery->name;?></div>
            <div><?=$model->delivery->name;?></div>
            <? if($model->delivery_id != 1):?>
                <div>Адрес доставки: <?=$model->address;?></div>
                <div>Стоимость доставки: <?=$model->delivery_price;?></div>
            <? else:?>
                <div>Пункт самовывоза: <?=$model->pickupPoint->name;?></div>
            <? endif;?>

            <div>Комментарий: <?=$model->comment;?></div>
        </td>
    </tr>

    <tr>
        <td>Информация о оплате</td>
        <td>
            <div>Тип оплаты: <?=$model->payment->name;?></div>
        </td>
    </tr>
</table>


<p>&nbsp;</p>
<b>Содержание заказа</b><br>
<p>&nbsp;</p>

<table width="100%" border="1">
    <tr>
        <th>Кол-во</th>
        <th>Название</th>
        <th>Артикул</th>
        <th>Цена</th>
        <th>Всего</th>
    </tr>
    <? foreach ($model->items as $k=>$v):?>
        <tr>
            <td><?=$v->qty?></td>
            <td>
                <?=$v->name?>
                <? if($v->color != ""):?>Цвет: <b><?=$v->color;?></b><br><? endif;?>
                <? if($v->size != ""):?>Размер: <b><?=$v->size;?></b><br><? endif;?>
            </td>
            <td><?=$v->item->id?></td>
            <td><?=\Yii::$app->functions->getPrice($v->price);?> руб.</td>
            <td><?=\Yii::$app->functions->getPrice($v->price*$v->qty);?> руб.</td>
        </tr>
    <? endforeach; ?>


    <tr>
        <td colspan="4">Промежуточный итог</td>
        <td><?=\Yii::$app->functions->getPrice($prices["result_price"]);?> руб.</td>
    </tr>

    <? if($model->adding_price > 0):?>
        <tr>
            <td colspan="4">Добавочная стоимость</td>
            <td><?=\Yii::$app->functions->getPrice($model->adding_price);?> руб.</td>
        </tr>
    <? endif;?>

    <? if($model->delivery_price > 0):?>
    <tr>
        <td colspan="4">Доставка</td>
        <td><?=\Yii::$app->functions->getPrice($model->delivery_price);?> руб.</td>
    </tr>
    <? endif;?>

    <? if($model->discount_value > 0):?>
        <tr>
            <td colspan="4">Скидка</td>
            <td><?=$model->discount_value;?> <?=($model->discount_type == 1 ? "%" : "руб.");?></td>
        </tr>
    <? endif;?>

    <? if($model->promocode != ""):?>
        <tr>
            <td colspan="4">Промо-код</td>
            <td><?=$model->promocode;?></td>
        </tr>
    <? endif;?>

    <? if($model->adding_price_text != ""):?>
        <tr>
            <td colspan="4">Дополнительно</td>
            <td><?=$model->adding_price_text;?></td>
        </tr>
    <? endif;?>

    <tr>
        <td colspan="4"><b>Всего</b></td>
        <td><?=\Yii::$app->functions->getPrice($prices["result_price"]+$model->delivery_price+$model->adding_price);?> руб.</td>
    </tr>
</table>




