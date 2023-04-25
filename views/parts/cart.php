
<? if(count(\Yii::$app->params['cart']['items']) > 0):?>
    
    <div class="fly-backet-list">
        <? foreach(\Yii::$app->params['cart']['items'] as $k=>$v):?>
            <?= $this->render('/catalog/parts/cart_item', ['v' => $v, 'cart' => false, 'change' => false] ); ?>
        <? endforeach;?>
    </div>

    <div class="cart_buy-bar">        
        <div class="d-flex justify-content-between">
            <span><?=Yii::$app->langs->t("Стоимость");?>:</span>
            <span class="fly-backet-total">
                <?=\Yii::$app->catalog->currencyPrice(new app\models\Items(), "price", "", "", \Yii::$app->params['cart']["price"]);?>                
            </span>
        </div>        
    </div>

    <div class="pt-4 pb-4 text-right">
        <a href="/cart" class="go-to-cart-link"><?=Yii::$app->langs->t("Просмотреть корзину");?></a>
    </div>

    <div class="text-center">
        <a href="/cart" class="btn btn-dark">Оформить заказ</a>
    </div>

<? else:?>

    <div class="cart_item">
        <span class="cart_clear"><?=Yii::$app->langs->t("Ваша корзина пуста");?></span>
    </div>
<? endif;?>
