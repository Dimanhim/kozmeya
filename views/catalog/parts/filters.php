<?
$subs = [];
Yii::$app->catalog->forceSubs($category->id, $subs);
?>


<div class="catalog_filter">

    <div class="container">
        <form method="get" class="filters pjaxFilters" data-grid="#catalogGrid" action="/<?=$uri;?>" data-pjax="0">
        
            <ul class="filter-nav d-flex">

                <? if(isset(Yii::$app->params["allCategoriesPid"][$category->parent])): $chunks = array_chunk(Yii::$app->params["allCategoriesPid"][$category->parent], ceil(count(Yii::$app->params["allCategoriesPid"][$category->parent])/2) );?>

                    <li>
                        <a href="javascript:void(0);">
                            Модель<span class="icon-caret"></span>
                        </a>
                        <? $count = ceil(count(Yii::$app->params["allCategoriesPid"][$category->parent])/2)?>

                        <div class="filter-box">
                            <div class="filter-list">
                                <div class="row">                                                
                					<? for ($i=0; $i<$count; $i++): ?>
                						<? if ( isset($chunks[$i]) ): ?>            			
                						<div class="col-md-6">
                	                        <? foreach($chunks[$i] as $index => $vv): ?>
                	                            
                                                <label class="control control-radio" for="filter_value_m<?=$vv->id;?>">
                                                    <?=$vv->name;?>

                                                    <input class="cats" type="radio" id="filter_value_m<?=$vv->id;?>" name="filters[categories]" value="<?=$vv->id;?>" <?=(isset($query["filters"]['categories']) && $query["filters"]['categories'] == $vv->id ? "checked" : "")?>>
                                                    <div class="control_indicator"></div>
                                                </label>
                	                           
                	                        <? endforeach;?>
                	                     </div>
                                        <? endif; ?>
                                    <? endfor; ?>
                                </div>
                            </div>

                            <div class="filter-box-footer">                                
                                <div class="row">
                                    <div class="col-lg-9">
                                        <div class="filter-btn">                                        
                                            <button type="submit" class="btn-dark"><?=Yii::$app->langs->t("Применить");?></button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="text-center">
                                            <div class="filter-clear-link">                                            
                                                <a href="/<?=$uri;?>"><?=Yii::$app->langs->t("Очистить");?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                        </div><!--.filter-box-->
                    </li>

                <? endif;?>

                <? $colors = \app\models\Colors::find()->alias("t")->joinWith(["items", "items.categories"])->where("t.vis = '1' AND items.vis = '1' AND categories.id IN (".implode(",", $subs).")")->orderBy("t.posled")->all();?>
                <? if($colors && count($gm) > 1): $chunks = array_chunk($colors, ceil(count($colors)/3));?>
                    <li>
                        <a href="javascript:void(0);">
                            Цвет<span class="icon-caret"></span>
                        </a>
                        <div class="filter-box">
                            <div class="filter-list">
                                <div class="row">
                                    <? foreach($chunks as $index => $chunk):?>
                                    <div class="col-md-6">
                                        <? foreach ($chunk as $kk=>$vv):?>
                                            <label class="control control-radio" for="filter_value_c<?=$vv->id;?>">
                                                <?=$vv->name;?>

                                                <input class="" type="checkbox" id="filter_value_c<?=$vv->id;?>" name="filters[colors][<?=$vv->id;?>]" value="<?=$vv->id;?>" <?=(isset($query["filters"]['colors'][$vv->id]) ? "checked" : "")?>>
                                                <div class="control_indicator"></div>
                                            </label>
                                        <? endforeach;?>
                                    </div>
                                    <? endforeach;?>
                                </div>                                
                            </div>                           

                            <div class="filter-box-footer">                                
                                <div class="row">
                                    <div class="col-lg-9">
                                        <div class="filter-btn">                                        
                                            <button type="submit" class="btn-dark"><?=Yii::$app->langs->t("Применить");?></button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="text-center">
                                            <div class="filter-clear-link">                                            
                                                <a href="/<?=$uri;?>"><?=Yii::$app->langs->t("Очистить");?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                        </div>
                    </li>
                <? endif;?>

                <? $sizes = \app\models\Sizes::find()->alias("t")->joinWith(["items", "items.categories"])->where("t.vis = '1' AND items.vis = '1' AND categories.id IN (".implode(",", $subs).")")->orderBy("t.posled")->all();?>
                <? /*if($sizes && count($gm) > 1): $chunks = array_chunk($sizes, ceil(count($sizes)/3));?>
                    <li>
                        <a href="javascript:void(0);">
                            Размер<span class="icon-caret"></span>
                        </a>
                        <div class="filter-box">
                            <div class="filter-list">
                                <div class="row">                                    
                                    <? foreach($chunks as $index => $chunk):?>
                                        <div class="col-md-6">
                                            <? foreach ($chunk as $kk=>$vv):?>
                                                <label class="control control-radio" for="filter_value_r<?=$vv->id;?>">
                                                    <?=$vv->name;?>

                                                    <input class="" type="checkbox" id="filter_value_r<?=$vv->id;?>" name="filters[sizes][<?=$vv->id;?>]" value="<?=$vv->id;?>" <?=(isset($query["filters"]['sizes'][$vv->id]) ? "checked" : "")?>>
                                                    <div class="control_indicator"></div>
                                                </label>
                                            <? endforeach;?>
                                        </div>
                                    <? endforeach;?>
                                </div>
                                
                            </div>
                            <div class="filter-box-footer">
                                <button type="submit" class="btn-dark">Применить</button>
                            </div>
                        </div>
                        
                    </li>
                <? endif;*/?>

                <? if($brands && count($gm) > 1): $chunks = array_chunk($brands, ceil(count($brands)/3));?>
                    <li>
                        <a href="javascript:void(0);">
                            Коллекция<span class="icon-caret"></span>
                        </a>
                        <div class="filter-box">
                            <div class="filter-list">
                                <div class="row">
                                    <? foreach($chunks as $index => $chunk):?>
                                        <div class="col-md-6">
                                            <? foreach ($chunk as $kk=>$vv):?>
                                                <label class="control control-radio" for="filter_value_k<?=$vv->id;?>">
                                                    <?=$vv->name;?>

                                                    <input class="" type="checkbox" id="filter_value_k<?=$vv->id;?>" name="filters[brands][<?=$vv->id;?>]" value="<?=$vv->id;?>" <?=(isset($query["filters"]['brands'][$vv->id]) ? "checked" : "")?>>
                                                    <div class="control_indicator"></div>
                                                </label>
                                            <? endforeach;?>
                                        </div>
                                    <? endforeach;?>  
                                </div>                              
                            </div>                             

                            <div class="filter-box-footer">                                
                                <div class="row">
                                    <div class="col-lg-9">
                                        <div class="filter-btn">                                        
                                            <button type="submit" class="btn-dark"><?=Yii::$app->langs->t("Применить");?></button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="text-center">
                                            <div class="filter-clear-link">                                            
                                                <a href="/<?=$uri;?>"><?=Yii::$app->langs->t("Очистить");?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>   
                            </div>

                        </div>

                    </li>
                <? endif;?>

                <? if($filters && count($gm) > 1) foreach($filters as $k=>$v):?>
                <li>
                    <a href="javascript:void(0);">
                        <?=$v->name;?><?=($v->ext != "" ? "(".$v->ext.")" : "");?><span class="icon-caret"></span>
                    </a>
                    <div class="filter-box">
                        <div class="three">
                            <? if($v->type_id == 1): //Brands?>
                                <? if($brands): $chunks = array_chunk($brands, ceil(count($brands)/3));?>
                                    <div class="row">
                                        <? foreach($chunks as $index => $chunk):?>
                                            <div class="col-md-6">
                                                <? foreach ($chunk as $kk=>$vv):?>
                                                <label class="control control-radio" for="filter_value_<?=$vv->id;?>">
                                                    <?=($v->static ? "<a href='/".$surl."/".$v->alias."/".$vv->alias."'>".$vv->name."</a>" : $vv->name);?>

                                                    <input type="checkbox" id="filter_value_<?=$vv->id;?>" name="filters[brands][<?=$vv->id;?>]" value="<?=$vv->id;?>" <?=(isset($query["filters"]['brands'][$vv->id]) ? "checked" : "")?>>
                                                    <div class="control_indicator"></div>
                                                </label>
                                                <? endforeach;?>
                                            </div>
                                        <? endforeach;?>
                                    </div>
                                <? endif;?>

                            <? elseif($v->type_id == 2): // Props?>
                                <? if($v->showtype_id == 1): //Checkbox?>
                                    <? if($v->values): $chunks = array_chunk($v->values, ceil(count($v->values)/3));?>
                                        <div class="row">
                                            <? foreach($chunks as $index => $chunk):?>
                                                <div class="col-md-6">
                                                    <? foreach ($chunk as $kk=>$vv):?>
                                                        <? if($vv->prop_id != 0):?>
                                                            <? if($values = Yii::$app->catalog->getFilterPropValues($vv->prop_id)) foreach ($values as $kkk => $vvv): ?>
                                                                <label class="control control-radio" for="filter_value_<?=$vv->id;?>">
                                                                    <?=$vvv["value"];?> (<?=(!isset(\Yii::$app->params['filter']['counts'][$vv->prop_id][$vvv["value"]]) ? "0" : \Yii::$app->params['filter']['counts'][$vv->prop_id][$vvv["value"]]);?>)

                                                                    <input type="checkbox" id="filter_value_<?=$vv->id;?>" name="filters[props_id][<?=$vv->prop_id;?>][<?=$vvv["value"];?>]" value="<?=$vv->id;?>" <?=(isset($query["filters"]['props_id'][$vv->prop_id][$vvv["value"]]) ? "checked" : "")?>>
                                                                    <div class="control_indicator"></div>
                                                                </label>
                                                            <? endforeach;?>
                                                        <? else:?>
                                                            <label class="control control-radio" for="filter_value_<?=$vv->id;?>">
                                                                <?=($v->static ? "<a href='/".$surl."/".$v->alias."/".$vv->alias."'>".$vv->name."</a>" : $vv->name);?>

                                                                <input type="checkbox" id="filter_value_<?=$vv->id;?>" name="filters[props][<?=$v->id;?>][<?=$vv->id;?>]" value="<?=$vv->id;?>" <?=(isset($query["filters"]['props'][$v->id][$vv->id]) ? "checked" : "")?>>
                                                                <div class="control_indicator"></div>
                                                            </label>
                                                        <? endif;?>
                                                    <? endforeach;?>
                                                </div>
                                            <? endforeach;?>
                                        </div>

                                    <? endif;?>
                                <? elseif($v->showtype_id == 4): //Slider?>
                                    <? foreach($v->values as $kk=>$vv):?>
                                        <? foreach($vv->values as $kkk=>$vvv):?>
                                            <?
                                            $min = $vvv->from_value;
                                            $max = $vvv->to_value;
                                            $getFrom = $min;
                                            $getTo = $max;
                                            $getFrom = (isset($query["filters"]['props'][$v->id][$vv->id]['from']) && $query["filters"]['props'][$v->id][$vv->id]['from'] >=0) ? $query["filters"]['props'][$v->id][$vv->id]['from'] : $getFrom;
                                            $getTo = (isset($query["filters"]['props'][$v->id][$vv->id]['to']) && $query["filters"]['props'][$v->id][$vv->id]['to'] > 0) ?$query["filters"]['props'][$v->id][$vv->id]['to'] : $getTo;
                                            ?>

                                            <input type="text" value="<?=$getFrom;?>" min="0" max="<?=$max;?>" name="filters[props][<?=$v->id;?>][<?=$vv->id;?>][from]">
                                            <input type="text" value="<?=$getTo;?>" min="0" max="<?=$max;?>" name="filters[props][<?=$v->id;?>][<?=$vv->id;?>][to]">
                                            <input type="text" value="" data-min="<?=$min;?>" data-max="<?=$max;?>">
                                        <? endforeach;?>
                                    <? endforeach;?>
                                <? endif;?>
                            <? elseif($v->type_id == 3): // Price?>
                                <?


                                $queryPrice = Yii::$app->db->createCommand("SELECT MAX(t._system_dynamic_price) as max, MIN(t._system_dynamic_price) as min FROM items t LEFT JOIN categoriestoitems t2 ON t2.item_id = t.id WHERE t2.category_id IN (".implode(",", $subs).") AND t.vis = '1' AND t._system_dynamic_price <> '0'")->queryOne();

                                $max = intval($queryPrice['max']);
                                $min = intval($queryPrice['min']);

                                if($max > 0):
                                    $getFrom = 0;
                                    $getTo = $max;

                                    $getFrom = (isset($query["filters"]['prices']['from']) && $query["filters"]['prices']['from'] >=0) ? $query["filters"]['prices']['from'] : $getFrom;
                                    $getTo = (isset($query["filters"]['prices']['to']) && $query["filters"]['prices']['to'] > 0) ? $query["filters"]['prices']['to'] : $getTo;
                                    ?>
                                        <input type="text" value="<?=$getFrom;?>" min="0" max="<?=$max;?>" name="filters[prices][from]">
                                        <input type="text" value="<?=$getTo;?>" min="0" max="<?=$max;?>" name="filters[prices][to]">
                                        <input type="text" value="" data-min="<?=$min;?>" data-max="<?=$max;?>">
                                <? endif;?>
                            <? elseif($v->type_id == 4): //Vars?>
                                <? if($v->showtype_id == 1): //Checkbox?>
                                    <? if($v->values): $chunks = array_chunk($v->values, ceil(count($v->values)/3));?>
                                        <div class="row">
                                            <? foreach($chunks as $index => $chunk):?>
                                                <div class="col-md-6">
                                                    <? foreach ($chunk as $kk=>$vv):?>
                                                        <label class="control control-radio" for="filter_value_<?=$vv->id;?>">
                                                            <?=($v->static ? "<a href='/".$surl."/".$v->alias."/".$vv->alias."'>".$vv->name."</a>" : $vv->name);?>

                                                            <input type="checkbox" id="filter_value_<?=$vv->id;?>" name="filters[vars][<?=$v->id;?>][<?=$vv->id;?>]" value="<?=$vv->id;?>" <?=(isset($query["filters"]['vars'][$v->id][$vv->id]) ? "checked" : "")?>>
                                                            <div class="control_indicator"></div>
                                                        </label>
                                                    <? endforeach;?>
                                                </div>
                                            <? endforeach;?>
                                        </div>
                                    <? endif;?>
                                <? endif;?>
                            <? endif;?>
                            
                        </div>
                        

                        <div class="filter-box-footer">                                
                            <div class="row">
                                <div class="col-lg-9">
                                    <div class="filter-btn">                                        
                                        <button type="submit" class="btn-dark"><?=Yii::$app->langs->t("Применить");?></button>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="text-center">
                                        <div class="filter-clear-link">                                            
                                            <a href="/<?=$uri;?>"><?=Yii::$app->langs->t("Очистить");?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                        </div>


                    </div>
                </li>
                <? endforeach;?>

                <li class="ml-auto" id="sort">
                    <?= $this->render('/catalog/parts/sorts', [
                        'query' => $query,
                        'uri' => $uri,
                        'getParams' => $getParams,
                    ] ); ?>
                </li>
            </ul>
        </form>
    </div>
</div>