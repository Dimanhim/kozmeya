<tr class="orderItemsRow connectedSortable" <?=(!isset($item) ? "style='display:none;'" : "")?>>
    <td>
        <? if(isset($item->item->images) && $item->item->images != ""):?>
            <img src="<?=\Yii::$app->functions->getUploadItem($item->item, "images", "fx", "50x50");?>">
        <? endif;?>
    </td>
    <td>
        <? if($edit):?>
            <input type="hidden" class="OrdersItemsRowId" name="OrdersItems[id][]" value="<?=(isset($item) && $item ? $item->id : "0")?>" />
            <input type="hidden" class="OrdersItems_item_id" name="OrdersItems[item_id][]" value="<?=(isset($item) && $item ? $item->item_id : "0")?>" />

            <input type="text" min="1" class="OrdersItems_qty form-control orderItemsRowChanger" name="OrdersItems[qty][]" value="<?=(isset($item) && $item ? $item->qty : "1")?>" />
        <? else:?>
            <?=$item->qty?>
        <? endif;?>
    </td>
    <td>
        <? if($edit):?>
            <input type="text" class="OrdersItems_name form-control" name="OrdersItems[name][]" value="<?=(isset($item) && $item ? $item->name : "")?>" />
        <? else:?>
            <?=$item->name?>
            <? if($item->vars):?>
                <ul>
                    <? foreach($item->vars as $kkk=>$vvv):?>
                        <li><?=$vvv->var->name;?>:<?=$vvv->name;?></li>
                    <? endforeach;?>
                </ul>
            <? endif;?>
        <? endif;?>
    </td>
    <td><?=(isset($item) && $item ? $item->item_id : "")?></td>
    <td>
        <? if($edit):?>
            <input type="text" class="OrdersItems_price form-control orderItemsRowChanger" name="OrdersItems[price][]" value="<?=(isset($item) && $item ? $item->price : "0")?>" />
        <? else:?>
            <?=\Yii::$app->functions->getPrice($item->price);?> руб.
        <? endif;?>
    </td>
    <td><span class="OrdersItemsPrice"><?=(isset($item) && $item ? \Yii::$app->functions->getPrice($item->price*$item->qty) : "")?></span> руб.</td>
    <td>
        <? if($edit):?>
            <a href="" class="btn btn-danger orderItemsRemove"><i class="fa fa-remove"></i></a>
        <? endif;?>
        <? if(isset($br) && $br):?><br><? endif;?>
    </td>
</tr>