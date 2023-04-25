<?
$percent = 0;
$priceField = "_system_dynamic_price";

if(!isset($cart) && $v->old_price > 0 && $v->old_price != $v->_system_dynamic_price)
{
    $percent = round((1 - $v->_system_dynamic_price / $v->old_price) * 100);
}
?>

<? if(isset($catalog) && $catalog):?>
    <div class="product">
        <div class="item_image">
            <? if($v->special):?><div class="item_sale"><span>SALE</span></div><? endif;?>

            <a href="<?=\Yii::$app->catalog->itemUrl($v);?>" class="product-img">
                <img src="<?=\Yii::$app->functions->getUploadItem($v, "images", "fx", "263x396");?>" class="w-100">
                <img src="<?=\Yii::$app->functions->getUploadItemNext($v, "images", "fx", "263x396");?>" class="product-hover-img w-100">
            </a>

        </div>
        <div class="item_name">
            <span class="item_name-left"><a href="<?=\Yii::$app->catalog->itemUrl($v);?>"><?=Yii::$app->langs->modelt($v, "name");?></a></span>
            <span class="item_name-right"><a class="addToFav <?=(isset($favorites) && $favorites ? "inFav" : "");?>" data-id="<?=$v->id;?>" data-method="add"><i class="heart_icon <?=(isset(\Yii::$app->session["favorites"][$v->id]) ? "active" : "");?>"></i></a></span>
        </div>
        <div class="item_price">
            <span>
                <?=\Yii::$app->catalog->currencyPrice($v, $priceField, "<span class='itemPrice'>$</span>");?>
                <? if($percent > 0):?>
                    <small><?=\Yii::$app->catalog->currencyPrice($v, "old_price");?></small>                
                <? endif;?>
            </span>
        </div>
    </div>
<? endif;?>