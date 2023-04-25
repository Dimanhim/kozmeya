<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\Paginator;
use yii\widgets\Pjax;
?>


<div class="header_top">
    <div class="container">
        <div class="page-title d-flex align-items-center">
            <div><?=Yii::$app->langs->t("Моя учетная запись");?></div>
        </div>
    </div>
</div>

<div class="container">

    <div class="welcome_text">
        <span><?=Yii::$app->langs->t("Добро пожаловать");?>, <?=Yii::$app->siteuser->identity->name;?></span>
        <a href="/profile/logout"><?=Yii::$app->langs->t("Выйти");?></a>
    </div>

    <div class="product_description lk">
        <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
            
            <ul class="nav nav-tabs lk-nav justify-content-center" id="myTabs" role="tablist">
                <li>
                    <a href="#myorders" class="active" data-toggle="tab"><?=Yii::$app->langs->t("Мои заказы");?></a>
                </li>
                <li>
                    <a href="#fav" data-toggle="tab"><?=Yii::$app->langs->t("Избранное");?></a>
                </li>
                <li>
                    <a href="#lichn" data-toggle="tab"><?=Yii::$app->langs->t("Личные данные");?></a>
                </li>
            </ul>

            <? if(count($messages) > 0):?>
                <div class="msg msg-info">
                    <? foreach($messages as $message):?>
                        <div><?=$message;?></div>
                    <? endforeach;?>
                </div>
            <? endif;?>

            <div class="tab-content user-information">
                <div class="tab-pane fade show active" role="tabpanel" id="myorders" aria-labelledby="myorders_view-tab">
                    
                    <? if(isset($order) && $order): $prices = Yii::$app->catalog->orderPrice($order);?>
                    <div class="full_item-buy">
                        <div class="tracking_section">
                            <div class="lk_block-title">
                                <a href="/profile"><?=Yii::$app->langs->t("Назад ко всем заказам");?></a><br>
                                <span><?=Yii::$app->langs->t("Номер заказа");?>: <small><?=$order->id;?></small></span>
                            </div>

                            <table class="table3">
                                <thead>
                                    <tr>
                                        <td><?=Yii::$app->langs->t("Дата заказа");?>:</td>
                                        <td><?=Yii::$app->langs->t("Способ доставки");?>:</td>
                                        <td><?=Yii::$app->langs->t("Номер отслеживания");?>:</td>
                                        <td><?=Yii::$app->langs->t("Получатель");?>:</td>
                                        <td><?=Yii::$app->langs->t("Адрес");?>:</td>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?=date("d/M/Y", strtotime($order->date));?></td>
                                    <td><?=$order->delivery->name;?></td>
                                    <td><?=$order->track;?></td>
                                    <td><?=$order->name;?></td>
                                    <td style="width:21%;"><?=$order->address;?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <span class="mini_title"><?=Yii::$app->langs->t("Способ оплаты");?>:</span>
                        <div class="payment_method">
                            <p><?=$order->payment->name;?></p>
                        </div>

                        <div class="tracking_section">
                            <table class="table4">
                                <thead>
                                <tr>
                                    <td><?=Yii::$app->langs->t("Товары");?></td>
                                    <td><?=Yii::$app->langs->t("Цена");?></td>
                                    <td><?=Yii::$app->langs->t("Количество");?></td>
                                    <td><?=Yii::$app->langs->t("Стоимость");?></td>
                                </tr>
                                </thead>
                                <tbody>
                                    <? foreach ($order->items as $k=>$v):?>
                                        <tr>
                                            <td>
                                                <div class="tracking_image">
                                                    <img src="<?=\Yii::$app->functions->getUploadItem($v->item, "images", "fx", "100x150");?>" alt="<?=$v->item->name;?>">
                                                </div>
                                                <div class="tracking_descr">
                                                    <span class="track_title">
                                                        <a href="<?=\Yii::$app->catalog->itemUrl($v->item);?>"><?=$v->item->name;?> <small><?=$v->item->id;?></small></a>
                                                    </span>

                                                    <span class="track_descr">
                                                        Код: <b><?=$v->item->id;?></b><br>
                                                        <? if($v->vars) foreach($v->vars as $kkk=>$vvv):?>
                                                            <?=$vvv->var->name;?>: <b><?=$vvv->name;?></b><br>
                                                        <? endforeach;?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td><?=$v->qty;?> &#8381;</td>
                                            <td>1</td>
                                            <td><?=\Yii::$app->functions->getPrice($v->price*$v->qty);?> &#8381;</td>
                                        </tr>
                                    <? endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <div class="summary_price">
                            <table class="table5">
                                <thead>
                                <tr>
                                    <td>
                                        <?=Yii::$app->langs->t("Стоимость товаров");?>:
                                    </td>
                                    <td>
                                        <span><?=$prices["items_price"];?> &#8381;</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?=Yii::$app->langs->t("Стоимость доставки");?>:
                                    </td>
                                    <td>
                                        <span><?=$order->delivery_price;?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?=Yii::$app->langs->t("Итоговая стоимость");?></td>
                                    <td><span class="summary"><?=$prices["result_price"]+$order->delivery_price;?> &#8381;</span></td>
                                </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
                    <? else:?>
                        <? if(!$model->orders):?>
                        <div class="lk_wrap">
                            <div class="lk_block-title">
                                <?=Yii::$app->langs->t("У Вас еще нет заказов");?><br><br><small><?=Yii::$app->langs->t("Зайдите в наш магазин, добавьте товар в корзину и сделайте ваш первый заказ");?></small>
                            </div>
                            <a href="/" class="lk_btn"><?=Yii::$app->langs->t("В магазин");?></a>
                        </div>
                        <? else:?>
                        <div class="orders_list">
                            <span class="my_title"><?=Yii::$app->langs->t("Мои заказы");?></span>
                            <table class="table2">                                
                                <thead>
                                    <tr>
                                        <td><?=Yii::$app->langs->t("Дата");?></td>
                                        <td><?=Yii::$app->langs->t("Номер заказа");?></td>
                                        <td><?=Yii::$app->langs->t("Общая сумма");?></td>
                                        <td><?=Yii::$app->langs->t("Статус");?></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                <? foreach ($model->orders as $k=>$v): $prices = Yii::$app->catalog->orderPrice($v);?>
                                    <tr>
                                        <td>
                                            <div class="rearranged-th"><?=Yii::$app->langs->t("Дата");?></div>
                                            <?=date("d/m/Y", strtotime($v->date));?>
                                        </td>
                                        <td>
                                            <div class="rearranged-th"><?=Yii::$app->langs->t("Номер заказа");?></div>
                                            <?=$v->id;?>                                            
                                        </td>
                                        <td>
                                            <div class="rearranged-th"><?=Yii::$app->langs->t("Общая сумма");?></div>
                                            <i class="fa fa-rub" aria-hidden="true"></i> <?=$prices["result_price"]+$v->delivery_price;?>
                                        </td>
                                        <td>
                                            <div class="rearranged-th"><?=Yii::$app->langs->t("Статус");?></div>
                                            <?=$v->status->name;?>
                                        </td>
                                        <td>
                                            <a href="/profile?order=<?=$v->id;?>"><?=Yii::$app->langs->t("Посмотреть");?></a>
                                        </td>
                                    </tr>
                                <? endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <? endif;?>
                    <? endif;?>
                </div>

                <div class="tab-pane fade" id="fav">
                    
                    <? if(count(\Yii::$app->params['favorites']) == 0):?>
                    <div class="lk_wrap">
                        <div class="lk_block-title">
                            <?=Yii::$app->langs->t("У Вас еще нет любимых товаров");?><br><br>
                            <small><?=Yii::$app->langs->t("Зайдите в наш магазин, нажмите иконку");?> <i class="heart_icon"></i> <?=Yii::$app->langs->t("на странице товара, чтобы добавить его в этот список");?></small>
                        </div>
                        <a href="/" class="lk_btn"><?=Yii::$app->langs->t("В магазин");?></a>
                    </div>
                    <? else:?>
                    <div class="favorite_items">
                        <span class="my_title"><?=Yii::$app->langs->t("Мои избранные товары");?></span>
                        <div class="models_list">
                            <div class="row">
                                <? foreach (\Yii::$app->params['favorites'] as $k=>$v):?>
                                    <div class="col-6 col-lg-3 col-md-4">
                                        <?= $this->render( '/catalog/parts/item', ['v' => $v, 'catalog' => true, 'favorites' => true] ); ?>
                                    </div>
                                <? endforeach;?>
                            </div>
                        </div>
                    </div>
                    <? endif;?>
                </div>

                <div class="tab-pane fade" role="tabpanel" id="lichn" aria-labelledby="lichn_view-tab">
                    <span class="my_title"><?=Yii::$app->langs->t("Личные данные");?></span>
                    
                    <div class="info">
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <span class="my_title"><?=Yii::$app->langs->t("Личная информация");?>:</span>
                                <ul>
                                    <li><?=Yii::$app->langs->t("Имя");?>: <span><?=Yii::$app->siteuser->identity->name;?></span></li>
                                    <li><?=Yii::$app->langs->t("Фамилия");?>: <span><?=Yii::$app->siteuser->identity->last_name;?></span></li>
                                    <li><?=Yii::$app->langs->t("Страна");?>: <span><?=Yii::$app->siteuser->identity->country;?></span></li>

                                    <li><?=Yii::$app->langs->t("Электронная почта");?>: <span><?=Yii::$app->siteuser->identity->email;?></span></li>

                                    <li><a href="#" data-toggle="modal" data-target="#edit-modal" class="black_btn"><?=Yii::$app->langs->t("Изменить");?></a></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#passwd-modal" class="white_btn"><?=Yii::$app->langs->t("Сменить пароль");?></a></li>
                                </ul>
                            </div>

                            <div class="col-lg-6">
                                <span class="my_title"><?=Yii::$app->langs->t("Адрес");?>:</span>
                                <ul>
                                    <li><?=Yii::$app->langs->t("Индекс");?>: <span><?=Yii::$app->siteuser->identity->index;?></span></li>
                                    <li><?=Yii::$app->langs->t("Страна");?>: <span><?=Yii::$app->siteuser->identity->country;?></span></li>
                                    <li><?=Yii::$app->langs->t("Город");?>: <span><?=Yii::$app->siteuser->identity->city;?></span></li>
                                    <li><?=Yii::$app->langs->t("Адрес");?>: <span><?=Yii::$app->siteuser->identity->address;?></span></li>

                                    <li><a href="#" data-toggle="modal" data-target="#address-modal" class="black_btn"><?=Yii::$app->langs->t("Изменить");?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="modal fade" id="edit-modal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">        
            
            <div class="text-right">
                <span data-dismiss="modal" class="modal-close"></span>
            </div>
            
            <div class="modal-body">
                <div class="modal_title"><?=Yii::$app->langs->t("Изменение личной информации");?></div>
                <form method="post" action="/profile?tab=edit">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

                    <div class="form-group">
                        <label><?=Yii::$app->langs->t("Страна");?></label>
                        <select name="Users[country]">
                            <? foreach (\app\models\Deliverycountries::find()->where("vis = 1")->orderBy("posled")->all() as $k=>$v):?>
                                <option <?=(Yii::$app->siteuser->identity->country == Yii::$app->langs->modelt($v, "name") ? "selected" : "");?> value="<?=Yii::$app->langs->modelt($v, "name");?>"><?=Yii::$app->langs->modelt($v, "name");?></option>
                            <? endforeach;?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?=Yii::$app->langs->t("Имя");?></label>
                        <input class="form-control" type="text" onkeyup="preventDigits(this);" name="Users[name]" value="<?=Yii::$app->siteuser->identity->name;?>">
                    </div>

                    <div class="form-group">
                        <label><?=Yii::$app->langs->t("Фамилия");?></label>
                        <input class="form-control" type="text" onkeyup="preventDigits(this);" name="Users[last_name]" value="<?=Yii::$app->siteuser->identity->last_name;?>">
                    </div>
                    <div class="form-group">
                        <label><?=Yii::$app->langs->t("Телефон");?></label>
                        <input class="form-control" type="text" id="phone" name="Users[phone]" value="<?=Yii::$app->siteuser->identity->phone;?>">
                    </div>
                    <div class="form-group">
                        <label><?=Yii::$app->langs->t("Почта");?> <small id="valid"></small></label>
                        <input class="form-control" class="form-control" required type="text" id="email" name="Users[email]" value="<?=Yii::$app->siteuser->identity->email;?>">
                    </div>

                    <button type="submit" class="register_btn modal_btn"><?=Yii::$app->langs->t("Сохранить");?></button>
                </form>
            </div>

        </div>
    </div>
</div><!--#edit-modal-->

<div class="modal fade" id="passwd-modal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">        
            
            <div class="text-right">
                <span data-dismiss="modal" class="modal-close"></span>
            </div>
            
            <div class="modal-body">
                <div class="modal_title"><?=Yii::$app->langs->t("Смена пароля");?></div>
                <form method="post" action="/profile?tab=edit">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

                    <div class="form-group">
                        <label><?=Yii::$app->langs->t("Старый пароль");?></label>
                        <input class="form-control required" type="text" name="old_password">
                    </div>
                    <div class="form-group">
                        <label><?=Yii::$app->langs->t("Новый пароль");?></label>
                        <input class="form-control required" type="text" name="Users[password_hash]">
                    </div>

                    <div class="form-group">
                        <label><?=Yii::$app->langs->t("Повторите пароль");?></label>
                        <input class="form-control required" type="text" name="repeat_password">
                    </div>

                    <button type="submit" class="register_btn modal_btn"><?=Yii::$app->langs->t("Сохранить");?></button>
                </form>
            </div>

        </div>
    </div>
</div><!--#passwd-modal-->


<div class="modal fade" id="address-modal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">        
            
            <div class="text-right">
                <span data-dismiss="modal" class="modal-close"></span>
            </div>
            
            <div class="modal-body">
                <div class="modal_title"><?=Yii::$app->langs->t("Изменение адреса");?></div>
                <form method="post" action="/profile?tab=edit">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

                    <div class="form-group">
                        <label><?=Yii::$app->langs->t("Страна");?></label>
                        <select name="Users[country]">
                            <? foreach (\app\models\Deliverycountries::find()->where("vis = 1")->orderBy("posled")->all() as $k=>$v):?>
                                <option <?=(Yii::$app->siteuser->identity->country == Yii::$app->langs->modelt($v, "name") ? "selected" : "");?> value="<?=Yii::$app->langs->modelt($v, "name");?>"><?=Yii::$app->langs->modelt($v, "name");?></option>
                            <? endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?=Yii::$app->langs->t("Индекс");?></label>
                        <input class="form-control" type="text" name="Users[index]" value="<?=Yii::$app->siteuser->identity->index;?>">
                    </div>
                    <div class="form-group">
                        <label><?=Yii::$app->langs->t("Город");?></label>
                        <input class="form-control" type="text" name="Users[city]" value="<?=Yii::$app->siteuser->identity->city;?>">
                    </div>

                    <div class="form-group">
                        <label><?=Yii::$app->langs->t("Адрес");?></label>
                        <input class="form-control" type="text" name="Users[address]" value="<?=Yii::$app->siteuser->identity->address;?>">
                    </div>

                    <button type="submit" class="register_btn modal_btn"><?=Yii::$app->langs->t("Сохранить");?></button>
                </form>
            </div>

        </div>
    </div>
</div><!--#address-modal-->



