<? if(isset($table) && $table):?>
    <tr class="itemBlock" id="cart_item_id_<?=$v->id;?>">
        <td>
            <div class="d-flex">
                <div class="tracking_image">
                    <img src="<?=\Yii::$app->functions->getUploadItem($v->item, "images", "fx", "100x150");?>" alt="<?=Yii::$app->langs->modelt($v->item, "name");?>">
                </div>

                <div class="tracking_descr d-flex align-items-start flex-column">
                    <span class="track_title">
                        <a href="#">
                            <?=Yii::$app->langs->modelt($v->item, "name");?>
                        </a>
                    </span>

                    <span class="track_descr mt-auto mb-auto">
                        <?=Yii::$app->langs->t("Код");?>: <b><?=$v->item->id;?></b><br>
                        <? if($v->color != ""):?><?=Yii::$app->langs->t("Цвет");?>: <b><?=$v->color;?></b><br><? endif;?>
                        <? if($v->size != ""):?><?=Yii::$app->langs->t("Размер");?>: <b><?=$v->size;?></b><br><? endif;?>
                    </span>

                    <a href="/cart?delete=<?=$v->id;?>" class="item_btn-info"><?=Yii::$app->langs->t("Удалить товар");?></a>
                </div>
            </div>
        </td>
        <td><?=\Yii::$app->catalog->currencyPrice($v->item, "price", '<span class="itemOnePrice">$</span>', '', \Yii::$app->params['cart']['cartPrice'][$v->id]);?></td>
        <td>
            <select data-id="<?=$v->id;?>" name="qty" class="changeCartQty">
                <? for ($i=1; $i<=5; $i++):?>
                <option value="<?=$i;?>" <? if($i == $v->qty):?>selected<? endif;?>><?=$i?></option>
                <? endfor;?>
            </select> 
        </td>
        <td><?=\Yii::$app->catalog->currencyPrice($v->item, "price", '<span class="itemPrice">$</span>', '', \Yii::$app->params['cart']['cartPrice'][$v->id]*$v->qty);?></td>
    </tr>
<? else:?>

    <div class="fly-backet-item d-flex" id="cart_item_id_<?=$v->id;?>">
        <div class="fly-backet-item-img">
            <img src="<?=\Yii::$app->functions->getUploadItem($v->item, "images", "fx", "100x150");?>" alt="<?=Yii::$app->langs->modelt($v->item, "name");?>">
        </div>
        <div class="fly-backet-item-info pl-3 d-flex flex-column">
            <div class="mb-2">
                <span><?=Yii::$app->langs->modelt($v->item, "name");?></span>
                <small>
                    <?//=$v->item->small;?>
                    <? /*Код: <b><?=$v->item->id;?></b><br>
                    <? if($v->color != ""):?>Цвет: <b><?=$v->color;?></b><br><? endif;?>*/?>
                    <? if($v->size != ""):?><?=Yii::$app->langs->t("Размер");?>: <b><?=$v->size;?></b><br><? endif;?>
                </small>
            </div>
            <div class="mt-auto">
                <span>
                    <? if(!$change):?>
                        <?=Yii::$app->langs->t("Количество");?>: <?=$v->qty;?>
                    <? else:?>
                        <input name="qty" type="text" value="<?=$v->qty;?>" data-id="<?=$v->id;?>" class="changeCartQty">
                    <? endif;?>
                </span>
                <div>
                    <small>
                        <?=\Yii::$app->catalog->currencyPrice($v->item, "price", '<span class="itemPrice">$</span>', '', \Yii::$app->params['cart']['cartPrice'][$v->id]*$v->qty);?>
                    </small>
                </div>
            </div>
        </div>
    </div>

<? endif;?>
