<? $prices = \Yii::$app->catalog->orderPrice($model);?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Заказ #: <?=$model->id;?></title>
</head>

<body>
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <img src="/img/content/logo.png" style="width:100%; max-width:300px;">
                        </td>

                        <td>
                            Заказ #: <?=$model->id;?><br>
                            Дата: <?=Yii::$app->formatter->asDate(strtotime($model->date), "d MMMM yyyy");?><br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            <?=\Yii::$app->params['settings'][5];?>
                        </td>

                        <td>
                            <?=\Yii::$app->params['settings'][2];?><br>
                            <?=\Yii::$app->params['settings'][8];?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>



        <tr class="heading">
            <td>
                Наименование
            </td>

            <td>
                Кол-во
            </td>

            <td>
                Цена
            </td>
        </tr>

        <? foreach($model->items as $k=>$v):?>
        <tr class="item">
            <td>
                <?=$v->name;?>
            </td>

            <td>
                <?=$v->qty;?>
            </td>

            <td>
                <?=\Yii::$app->functions->getPrice($v->price*$v->qty);?>
            </td>
        </tr>
        <? endforeach;?>

        <? if($model->delivery_price > 0):?>
        <tr class="item">
            <td colspan="2">
                Доставка
            </td>

            <td>
                <?=\Yii::$app->functions->getPrice($model->delivery_price);?>
            </td>
        </tr>
        <? endif;?>

        <? if($model->discount_value > 0):?>
        <tr class="item">
            <td colspan="2">
                Скидка
            </td>

            <td>
                <?=$model->discount_value;?> <?=($model->discount_type == 1 ? "%" : "р.");?>
            </td>
        </tr>
        <? endif;?>

        <tr class="total">
            <td colspan="2">Итого</td>

            <td>
                <?=\Yii::$app->functions->getPrice($prices["result_price"]+$model->delivery_price+$model->adding_price);?>
            </td>
        </tr>
    </table>
</div>
</body>
</html>