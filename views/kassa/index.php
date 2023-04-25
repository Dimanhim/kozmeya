<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
?>

<main class="work__area">
    <div class="container">
        <header class="work__area-head">
            <?= $this->render( '/parts/bcrumbs', [] ); ?>

            <h1 class="h1-head"><?=Html::encode(\Yii::$app->meta->getPageTitle("Оплата заказа"));?></h1>
        </header>
        <div class="detail-shares-area">
            <? if($success):?>
                <h2>Заказ успешно оплачен</h2>
            <? elseif($error):?>
                <h2>Произошла ошибка</h2>
            <? elseif($form):?>
                <form action="<?=$config["shopPaymentUrl"];?>" method="post">
                    <input name="shopId" value="<?=$config['shopId'];?>" type="hidden"/>
                    <input name="scid" value="<?=$config['scid'];?>" type="hidden"/>
                    <input name="sum" value="<?=$result_price;?>" type="hidden">
                    <? if(isset($order->user_id) && $order->user_id != ""):?><input name="customerNumber" value="<?=$order->user_id;?>" type="hidden"/><? endif;?>
                    <input name="paymentType" value="AC" type="hidden"/>
                    <input name="orderNumber" value="<?=$order->id;?>" type="hidden"/>

                    <input name="cps_phone" value="<?=$order->phone;?>" type="hidden"/>

                    <input name="cps_email" value="<?=$order->email;?>" type="hidden"/>

                    <input type="submit" value="Оплатить"/>
                </form>
            <? endif;?>


            <?=\Yii::$app->meta->getSeoText();?>
        </div>
    </div>
</main>