<ul>
<? if($item->vars)  foreach($item->vars as $k=>$v):?>
    <li class="dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <?=Yii::$app->langs->modelt($v, "name");?> <span class="caret"></span>
        </a>

        <ul class="dropdown-menu">
            <li>
                <? if($v->values): $chunks = array_chunk($v->values, ceil(count($v->values)/2));?>
                    <? foreach($chunks as $index=>$chunk):?>
                    <div class="control-group <? if($index > 0):?>right<? endif;?>">
                        <? foreach ($chunk as $kk=>$vv):?>
                        <label class="control control-radio">
                            <? if($vv->value):?>
                                <? if($vv->value->files != ""):?>
                                    <img src="<?=\Yii::$app->functions->getUploadItem($vv->value, "files", "fx", "29x29");?>" alt="<?=$vv->value->name;?>">
                                <? else:?>
                                    <?=Yii::$app->langs->modelt($vv->value, "name");?>
                                <? endif;?>
                            <? else:?>
                                <? if($vv->images != ""):?>
                                    <img src="<?=\Yii::$app->functions->getUploadItem($vv, "images", "fx", "29x29");?>" alt="<?=$vv->name;?>">
                                <? else:?>
                                    <?=Yii::$app->langs->modelt($vv, "name");?>
                                <? endif;?>
                            <? endif;?>

                            <input class="changeVar" type="radio" id="vars_<?=$vv->id;?>" name="vars[<?=$v->id;?>]" data-image="<?=\Yii::$app->functions->getUploadItem($vv, "images", "fx", "245x550");?>" value="<?=$vv->id;?>">
                            <div class="control_indicator"></div>
                        </label>
                        <? endforeach;?>
                    </div>
                    <? endforeach;?>
                <? endif;?>
            </li>
        </ul>
    </li>
<? endforeach;?>
</ul>
