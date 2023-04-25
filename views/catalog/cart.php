<?
use yii\helpers\Html;
?>

<div class="container <? if($success):?>cart<? endif;?>">
    <? if($success):?>

        <div class="lk_wrap">
            <div class="lk_block-title">
                <?=Yii::$app->langs->t("Ваш заказ успешно отправлен!");?><br><br><big><?=Yii::$app->langs->t("НОМЕР ЗАКАЗА");?>: <?=$model->id;?></big>
                <small><?=Yii::$app->langs->t("Наш менеджер свяжется с Вами в ближайшие время для уточнения заказа.");?></small>
            </div>
            <a href="/" class="lk_btn" style="width: 220px;"><?=Yii::$app->langs->t("Вернуться в магазин");?></a>
        </div>
    <? else:?>

        <? if(count(\Yii::$app->params['cart']['items']) == 0):?>
            
            <div class="lk_wrap">
                <div class="lk_block-title">
                    <?=Yii::$app->langs->t("Корзина пуста");?>
                </div>
                <a href="/" class="lk_btn" style="width: 220px;"><?=Yii::$app->langs->t("Вернуться в магазин");?></a>
            </div>
        <? else:?>

            <div class="cart_order-menu">
                
                <ul>
                    <li class="<? if($step == 1):?>active<? endif;?>"><span>1</span><?=Yii::$app->langs->t("Корзина");?></li>
                    <? if(Yii::$app->siteuser->isGuest):?>
                    <li class="<? if($step == 3):?>active<? endif;?>"><span>2</span><?=Yii::$app->langs->t("Вход / Регистрация");?></li>
                    <li class="<? if($step == 3):?>active<? endif;?>"><span>3</span><?=Yii::$app->langs->t("Доставка и оплата");?></li>
                    <? else:?>
                        <li class="<? if($step == 2):?>active<? endif;?>"><span>2</span><?=Yii::$app->langs->t("Доставка и оплата");?></li>
                    <? endif;?>
                </ul>
            </div>

            <? if($step == 1):?>
                
                <h1 class="cart-title"><?=Yii::$app->langs->t("Корзина");?></h1>

                <div class="tracking_section">
                    <table class="cart-table table4">
                        <thead>
                            <tr>
                                <th><?=Yii::$app->langs->t("Товары");?></th>
                                <th><?=Yii::$app->langs->t("Цена");?></th>
                                <th><?=Yii::$app->langs->t("Количество");?></th>
                                <th class="td-collapse"><?=Yii::$app->langs->t("Стоимость");?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <? foreach(\Yii::$app->params['cart']['items'] as $k=>$v):?>
                                <?= $this->render('/catalog/parts/cart_item', ['v' => $v, 'change' => true, 'table' => true] ); ?>
                            <? endforeach;?>
                        </tbody>
                    </table>
                </div>

                <div class="summary_price cart_price">
                    
                    <div class="row flex-md-row-reverse align-items-end">                        
                        <div class="col-lg-4 col-md-6">                            
                            <div class="cartPrices">
                                <?= $this->render( '/catalog/parts/cart_prices', ['main' => false] ); ?>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-4 col-md-6 mr-auto">
                            <div class="footer_menu">
                                <span class="cart_small-text"><?=Yii::$app->langs->t("Если у вас есть промокод на скидку, введите его<br>в поле ниже и нажмите кнопку “Применить”");?></span>
                                <div class="footer_form">
                                    <form class="setPromocode" method="post" action="">
                                        <div class="input">
                                            <input required type="text" id="promo" placeholder="Введите промокод" name="code" value="<?=\Yii::$app->session["promocode"];?>">
                                        </div>
                                        <div class="button">
                                            <button type="submit">&gt;</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!--.cart_price-->

                <div class="buttons_cart">
                    <a href="/" class="disabled"><?=Yii::$app->langs->t("Вернуться в магазин");?></a>
                    <a href="/cart?step=<? if(Yii::$app->siteuser->isGuest):?>2<? else:?>3<? endif;?>"><?=Yii::$app->langs->t("Продолжить оформление");?></a>
                </div>
            <? elseif($step == 2):?>
                <h1><?=Yii::$app->langs->t("ВХОД / РЕГИСТРАЦИЯ");?></h1>

                <div class="reg cart_content">
                    
                    
                    <div class="row">
                        <div class="col-lg-5">                            
                            <div class="h4">
                                <?=Yii::$app->langs->t("Регистрация");?>:                                
                            </div>

                            <div class="privancy-policy">
                                <?=Yii::$app->langs->t("Пожалуйста, зарегистрируйтесь, чтобы создать учетную запись.<br>Все поля являются обязательными для заполнения.");?>
                            </div>

                            <form method="post" action="" id="registerForm">
                                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

                                <div class="form-group">
                                    <label><?=Yii::$app->langs->t("Страна");?></label>
                                    <select class="changeCartQty" name="country" onmousedown="if(this.options.length>5){this.classList.add('active');this.size=5;}"  onchange='this.size=5;' onblur="this.size=5;">
	                                        <? $data = \app\models\Deliverycountries::find()->where("id = 1")->orderBy("name")->one() ?>
	                                        <option value="<?=$data->id;?>" data-price="<?=$data->price;?>"><?=Yii::$app->langs->modelt($data, "name")?></option>
                                            <? foreach (\app\models\Deliverycountries::find()->where("vis = 1 AND id <> 1")->orderBy("name")->all() as $k=>$v):?>
                                                <option data-id="<?=$v->id;?>" data-price="<?=$v->price;?>" value="<?=Yii::$app->langs->modelt($v, "name");?>"><?=Yii::$app->langs->modelt($v, "name");?></option>
                                            <? endforeach;?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><?=Yii::$app->langs->t("Имя");?></label>
                                    <input class="form-control" required type="text" onkeyup="preventDigits(this);" name="name" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label><?=Yii::$app->langs->t("Фамилия");?></label>
                                    <input class="form-control" type="text" onkeyup="preventDigits(this);" name="last_name" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label><?=Yii::$app->langs->t("Телефон");?></label>
                                    <input class="form-control" type="tel" id="phone" name="phone" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label><?=Yii::$app->langs->t("Электронная почта");?> <small id="valid"></small></label>
                                    <input class="form-control" required type="text" id="email" name="email" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label><?=Yii::$app->langs->t("Пароль");?></label>                                    
                                    <div class="position-relative">
                                        <input class="form-control" required type="password" id="passwd_input" name="password" placeholder=""> 
                                        <span class="show-pass" id="show_passwd"><?=Yii::$app->langs->t("Показать");?></span>                   
                                    </div>
                                </div>

                                <div class="privancy-policy">
                                    <?=Yii::$app->langs->t("Создавая учетную запись, Вы соглашаетесь с условиями нашей");?>
                                    <a href="#"><?=Yii::$app->langs->t("Политики конфиденциальности");?>.</a>
                                </div>

                                <div class="registr-btn-wrap">
                                    <button type="submit" class="btn btn-dark btn-wm"><?=Yii::$app->langs->t("Регистрация");?></button>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-2 d-none d-xl-block d-lg-block">
                            <div class="form-separator"></div>
                        </div>

                        <div class="col-lg-5">
                            <span class="my_title"><?=Yii::$app->langs->t("Вход");?>:</span>

                            <form id="loginForm">
                                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                                
                                <div class="form-group">
                                    <input class="form-control" type="login" name="SiteLoginForm[username]" placeholder="<?=Yii::$app->langs->t("Адрес электронной почты");?>">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="password" name="SiteLoginForm[password]" placeholder="<?=Yii::$app->langs->t("Пароль");?>">
                                </div>

                                <a href="#" class="login_pre-text"><?=Yii::$app->langs->t("Забыли пароль?");?></a>
                                <button type="submit" class="btn btn-dark btn-wm login_btn"><?=Yii::$app->langs->t("Войти");?></button>                                
                            </form>
                        </div>
                    </div>
                </div>
            <? elseif($step == 3):?>
                <h1><?=Yii::$app->langs->t("ДОСТАВКА И ОПЛАТА");?></h1>

                <form id="orderForm" method="post">
                    
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

                    <div class="container">
                        <div class="reg">
                            <div class="row">

                                <div class="col-lg-5">
                                    <div class="h4">
                                        <?=Yii::$app->langs->t("ВЫБЕРИТЕ СПОСОБ ДОСТАВКИ И ОПЛАТЫ");?>                                    
                                    </div>

                                    <div class="form-group">
                                        <select name="Orders[delivery_id]" class="ddd">
                                            <? foreach($deliveries as $k=>$v):?>
                                                <option value="<?=$v->id;?>"><?=$v->name;?></option>
                                            <? endforeach;?>
                                        </select>
                                    </div>
                                    <? $pickup = app\models\Pickuppoints::find()->where("vis = '1'")->orderBy("posled")->all(); ?>
                                    <div class="form-group pickup_d">
	                                    <select name="Orders[pickup_point_id]" class="pickup">

		                                    <? foreach ($pickup as $v ): ?>
		                                    	<option value="<?=$v->id?>">Пункт самовывоза: <?=$v->name?></option>
		                                    <? endforeach; ?>
	                                    </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <select name="Orders[payment_id]">
                                            <? foreach($payments as $k=>$v):?>
                                                <option class="paymentdelivery <? if($v->deliveries) foreach ($v->deliveries as $kk=>$vv):?>paymentdelivery_<?=$vv->id;?><? endforeach;?>" value="<?=$v->id;?>"><?=$v->name;?></option>
                                            <? endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 d-none d-xl-block d-lg-block">
                                    <div class="form-separator"></div>
                                </div>
                                <div class="col-lg-5">
                                    
                                    <div class="mb-5">
                                        <div class="h4">
                                            <?=Yii::$app->langs->t("ПОЛУЧАТЕЛЬ");?><small><a href="#"><?=Yii::$app->langs->t("Заполнить из моих данных");?></a></small>
                                        </div>

                                        <div class="form-group">
                                            <input class="form-control" required="" type="text" name="Orders[name]" placeholder="Имя Фамилия" value="<?=(!Yii::$app->siteuser->isGuest ? Yii::$app->siteuser->identity->name : "");?>">
                                        </div>

                                        <div class="form-group">
                                            <input class="form-control" required="" type="email" name="Orders[email]" placeholder="E-mail" value="<?=(!Yii::$app->siteuser->isGuest ? Yii::$app->siteuser->identity->email : "");?>">
                                        </div>

                                        <div class="form-group">
                                            <input class="form-control" required="" type="tel" name="Orders[phone]" placeholder="Номер телефона" value="+7 (999) 999-9999">
                                        </div>
                                    </div>
									<div class="dadr" style="display: none;">		
                                    <div class="h4">
                                        <?=Yii::$app->langs->t("Адрес доставки");?>                                        
                                    </div>

                                    <div class="form-group">
                                        <input type="hidden" class="deliveryPrice" name="delivery_price" value="">

                                        <label><?=Yii::$app->langs->t("Страна");?></label>
                                        <select class="deliveryCountry" name="Orders[country]">
	                                        <? $data = \app\models\Deliverycountries::find()->where("id = 1")->orderBy("name")->one() ?>
	                                        <option value="<?=$data->id;?>" data-price="<?=$data->price;?>"><?=Yii::$app->langs->modelt($data, "name")?></option>
                                            <? foreach (\app\models\Deliverycountries::find()->where("vis = 1 AND id <> 1")->orderBy("name")->all() as $k=>$v):?>
                                                <option data-id="<?=$v->id;?>" data-price="<?=$v->price;?>" value="<?=Yii::$app->langs->modelt($v, "name");?>"><?=Yii::$app->langs->modelt($v, "name");?></option>
                                            <? endforeach;?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label><?=Yii::$app->langs->t("Индекс");?></label>
                                        <input class="form-control" type="text" name="Orders[index]" placeholder="Индекс" value="<?=(!Yii::$app->siteuser->isGuest ? Yii::$app->siteuser->identity->index : "");?>">
                                    </div>

                                    <div class="form-group">
                                        <label><?=Yii::$app->langs->t("Город");?></label>
                                        <select class="deliveryCities" name="Orders[city]">
                                            <? foreach (\app\models\Deliverycities::find()->where("vis = 1")->orderBy("name")->all() as $k=>$v):?>
                                                <option class="cityOption cityCountry_<?=$v->country_id;?>" data-price="<?=$v->price;?>" value="<?=Yii::$app->langs->modelt($v, "name");?>"><?=Yii::$app->langs->modelt($v, "name");?></option>
                                            <? endforeach;?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label><?=Yii::$app->langs->t("Город (если нет в списке)");?></label>
                                        <input class="form-control deliveryCity"  type="text" name="city_another" placeholder="Город" value="">
                                    </div>
                                    <div class="form-group">
                                        <label><?=Yii::$app->langs->t("Адрес");?></label>
                                        <input class="form-control" type="text" name="Orders[address]" placeholder="Адрес" value="<?=(!Yii::$app->siteuser->isGuest ? Yii::$app->siteuser->identity->address : "");?>">
                                    </div>
									</div>
                                </div>
                            </div>

                            <div class="tracking_section">
                                
                                <div class="h4">
                                    <?=Yii::$app->langs->t("данные заказа");?>
                                </div>

                                <table class="table3">
                                    <thead>
                                    <tr>
                                        <td><?=Yii::$app->langs->t("Дата заказа");?>:</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><?=date("d/m/Y в H:i");?></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <table class="cart-table table4">
                                    <thead>
                                    <tr>
                                        <td><?=Yii::$app->langs->t("Товары");?></td>
                                        <td><?=Yii::$app->langs->t("Цена");?></td>
                                        <td><?=Yii::$app->langs->t("Количество");?></td>
                                        <td><?=Yii::$app->langs->t("Стоимость");?></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <? foreach(\Yii::$app->params['cart']['items'] as $k=>$v):?>
                                        <?= $this->render('/catalog/parts/cart_item', ['v' => $v, 'change' => true, 'table' => true] ); ?>
                                    <? endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="summary_price cart_price">
                                <div class="row flex-md-row-reverse align-items-end">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="summary_price cartPrices">
                                            <?= $this->render( '/catalog/parts/cart_prices', ['main' => true] ); ?>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 mr-auto">
                                        <div class="cart_change">
                                            <a href="/cart"><?=Yii::$app->langs->t("Внести изменения в корзину");?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div><!--.reg-->                     
                    </div>
                    
                    <div class="client-agreement">
                        <div class="cg-content">
                            <input type="checkbox" name="apply"><?=Yii::$app->langs->t("Поставьте отметку, чтобы указать, что вы согласны с нашими");?>
                            <a href="#"><?=Yii::$app->langs->t("Условиями и<br>положениями");?></a> <?=Yii::$app->langs->t("и ознакомились с");?>
                            <a href="#"><?=Yii::$app->langs->t("Политикой конфиденциальности");?></a>.
                        </div>
                    </div><!--.client-agreement-->

                    <div class="buttons_cart">
                        <a href="/cart" class="disabled"><?=Yii::$app->langs->t("Предыдущий шаг");?></a>
                        <button type="submit"><?=Yii::$app->langs->t("Оплатить заказ");?></button>
                    </div>
                </form>
            <? endif;?>

        <? endif;?>
    <? endif;?>
</div>
