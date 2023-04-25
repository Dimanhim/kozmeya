<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$percent = 0;
if($item->old_price > 0 && $item->old_price != $item->price)
{
    $percent = round((1 - $item->price / $item->old_price) * 100);
}

$prices = \Yii::$app->catalog->itemPrice($item);
?>

<div class="container">
    <div class="product_bar">

        <div class="row">
            
            <div class="col-lg-9">
                <div class="product-photos">
                    <?= $this->render('/catalog/parts/photos', ['item' => $item] ); ?>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="product_title">
                    <?=Html::encode(\Yii::$app->meta->getPageTitle(Yii::$app->langs->modelt($item, 'name')));?>

                    <span class="item_name-right">
                        <a class="addToFav" data-id="<?=$item->id;?>" data-method="add" href="javascript:void(0);" tabindex="-1"><i class="heart_icon <?=(isset(\Yii::$app->session["favorites"][$item->id]) ? "active" : "");?>"></i></a>
                    </span>
                </div>

                <form class="addToCartForm">
                    <input type="hidden" name="method" value="add">
                    <input type="hidden" name="id" value="<?=$item->id;?>">
                    <input type="hidden" name="qty" value="1">

                    <div class="price">
                        <?= $this->render('/catalog/parts/prices', ['item' => $item] ); ?>
                    </div>

                    <div class="product_text"><?=Yii::$app->langs->modelt($item, "text");?></div>

                    <div class="product-filter">
                        
                        <div class="row">                                                
                            <? if($item->colors):?>
                                <div class="col-6">
                                    <div class="pf-item">
                                        <span>
                                            <?=Yii::$app->langs->t("Цвет");?><i class="icon-caret"></i>
                                        </span>
                                        <div class="pf-item-val"></div>
                                        <div class="pf-box">                                    
                                            <? $chunks = array_chunk($item->colors, ceil(count($item->colors)/2));?>
                                            <? foreach ($chunks as $index => $chunk):?>                                                
                                                <? foreach ($chunk as $data):?>
                                                    <label class="control control-radio price-control">
                                                        <?=Yii::$app->langs->modelt($data, "name");?>
                                                        <input type="radio" name="color" checked="checked" value="<?=Yii::$app->langs->modelt($data, "name");?>" />
                                                        <div class="control_indicator"></div>
                                                    </label>
                                                <? endforeach;?>                                                
                                            <? endforeach;?>                                    
                                        </div>
                                    </div>
                                </div>
                            <? endif;?>

                            <? if($item->sizes):?>
                                <div class="col-6">
                                    <div class="pf-item">
                                        <input type="hidden" name="size" value="" class="setItemSizeInput">
                                        <span>
                                            <?=Yii::$app->langs->t("Размер");?><i class="icon-caret"></i>
                                        </span>
                                        <div class="pf-item-val"></div>
                                        <div class="pf-box pf-box-size">
                                            <div class="row">
                                                <div class="col-6">
                                                    <? foreach ($item->sizes as $size):?>
                                                        <label class="control control-radio price-control">                                                            
                                                            <?=$size->name;?>
                                                            <input class="setItemSizeInput setItemSizeInput_<?=$size->id;?>" type="radio" name="size" checked="checked" value="<?=$size->name;?>" />
                                                            <div class="control_indicator"></div>
                                                        </label>
                                                    <? endforeach;?>
                                                </div>
                                                <div class="col-6">
                                                    <a href="#" class="choose-size" data-toggle="modal" data-target="#size_modal"><?=Yii::$app->langs->t("Подобрать размер");?></a>
                                                </div>
                                            </div>
                                        </div>                                       
                                    </div>
                                </div>
                            <? endif;?>
                        </div>
                    </div><!--.product-filter-->
					<? if ($item->status->id == 2): ?>
						<p style="margin-top: 20px;"><b>Нет в наличии</b></p>
					<? else: ?>
	                    <a href="javascript:void(0);" class="item_btn sub"><?=Yii::$app->langs->t("Добавить в корзину");?></a>
                    <? endif; ?>
                </form>

                <div class="product_text">
                    <?=Yii::$app->langs->t("Если вашего размера нет в наличии, мы можем оперативно отшить изделие по  вашим меркам, а также с учетом ваших пожеланий внести необходимые  корректировки (изменить цвет, длину,добавить/убрать рукава, шлейф, пояс,  изменить форму выреза и т.п.)");?>
                </div>

                <a href="#" class="item_btn-white" data-toggle="modal" data-target="#feedback-modal"><?=Yii::$app->langs->t("СВЯЗАТЬСЯ С НАМИ");?></a><br>
                <a href="/dostavka" class="item_btn-info"><?=Yii::$app->langs->t("Условия доставки");?></a>

                <div class="product_text text2">
                    <? if($item->props):?>
                        <? foreach($item->props as $prop):?>
                            <b><?=$prop->prop->name;?>:</b> <?=str_replace("|", ",", $prop->value);?><br>
                        <? endforeach;?>
                    <? endif;?>

                    <b><?=Yii::$app->langs->t("Товар");?>:</b> <?=$item->id;?><br>
                    <b><?=Yii::$app->langs->t("Сделано в России");?></b>
                </div>
            </div>

        </div>
    </div>

    <div class="product_description prod">
        <div class="splash-products" data-example-id="togglable-tabs">
            
            <ul class="nav nav-tabs justify-content-center" id="myTabs" role="tablist">
                <? if($similar):?>
                    <li role="presentation">
                        <a href="#rec" class="active" id="rec-tab" role="tab" data-toggle="tab" aria-controls="rec" aria-expanded="true"><?=Yii::$app->langs->t("Рекомендации");?></a>
                    </li>
                <? endif;?>

                <? if($also):?>
                    <li role="presentation" class="">
                        <a href="#prev_view" role="tab" id="prev_view-tab" data-toggle="tab" aria-controls="profile" aria-expanded="false"><?=Yii::$app->langs->t("Недавно просмотрено");?></a>
                    </li>
                <? endif;?>
            </ul>

            <div class="tab-content" id="myTabContent">
                <? if($similar):?>
                <div class="tab-pane fade active show" role="tabpanel" id="rec" aria-labelledby="rec-tab">
                    <div class="models_list">
                        <div class="product_slider">
                            <? foreach($similar as $k=>$v):?>
                                <div class="product_slider-item">
                                    <?= $this->render( '/catalog/parts/item', ['v' => $v, 'catalog' => true] ); ?>
                                </div>
                            <? endforeach;?>
                        </div>
                    </div>
                </div>
                <? endif;?>

                <? if($also):?>
                <div class="tab-pane fade <? if(!$similar):?>active<? endif;?>" role="tabpanel" id="prev_view" aria-labelledby="prev_view-tab">
                    <div class="models_list">
                        <div class="product_slider">
                            <? foreach($also as $k=>$v):?>
                                <div class="product_slider-item">
                                    <?= $this->render( '/catalog/parts/item', ['v' => $v, 'catalog' => true] ); ?>
                                </div>
                            <? endforeach;?>
                        </div>
                    </div>
                </div>
                <? endif;?>
            </div>
        </div>
    </div>

</div>



<? if($item->sizes):?>
<div class="modal fade" id="size_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    
    <div class="modal-content">        
        
        <div class="text-right">
            <span data-dismiss="modal" class="modal-close"></span>
        </div>
        
        <div class="modal-body">
            <table class="table">
                <tbody>
                <tr>
                    <td class="nobortop nobor"></td>
                    <? foreach ($item->sizes as $size):?>
                        <td class="nobortop" colspan="2"><?=$size->name;?></td>
                    <? endforeach;?>
                </tr>
                <? foreach (\app\models\SizesGroups::find()->where("vis = 1")->orderBy("posled")->all() as $group):?>
                    <tr>
                        <td class="nobor"><?=$group->name;?></td>
                        <? foreach ($item->sizes as $size):?>
                        <?php
                                $values = [];
                                foreach ($size->valuesData as $valueData) 
                                {
                                    $values[$valueData->group_id] = array_filter(explode(";", $valueData->name));
                                }
                        ?>
                        	<? if(isset($values[$group->id])): ?>
	                        	<? foreach ($values[$group->id] as $kk=>$vv): ?>
		                            <td class="pad" <?=(count($values[$group->id]) == 1 ? 'colspan="2"' : '')?>>
		                                <?
		                                //if(isset($values[$group->id])) foreach ($values[$group->id] as $value):?>
		                                    <span><a class="setItemSize" data-value="<?=$size->id;?>" data-size="<?=$size->name;?>" href="#"><?=$vv;?></a></span>
		                                <? //endforeach;?>
		                            </td>
	                            <? endforeach; ?>
                            <? endif; ?>
                        <? endforeach;?>
                    </tr>
                <? endforeach;?>
                </tbody>
            </table>
        </div>

    </div>

  </div>
</div>
<? endif;?>


