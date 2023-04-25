<?
$values = [];
$props = [];
$categories = [];

if(!$model->isNewRecord) {
    if($model->props) {
        foreach($model->props as $k=>$v){
            $values[$v->prop_id] = $v;
        }
    }

    if($model->categories) {
        foreach($model->categories as $k=>$v){
            $categories[$v->id] = $v->id;
        }
    }
}
?>
<? $props = \app\models\Props::find()->joinWith("categories")->where("props.vis = '1'".(count($categories) > 0 ? " AND categories.id IN (".implode(",", $categories).")" : ""))->orderBy("props.posled")->all();?>
<? if(isset($props) && $props) foreach($props as $k=>$v):?>
    <div class="row">
        <div class="form-group col-xs-6">
            <label class="control-label"><?=$v->name;?></label>
            <? if($v->values):?>
                <select name="props[<?=$v->id;?>][value]" class="form-control">
                    <?foreach($v->values as $kk=>$vv):?>
                        <option <?=(isset($values[$v->id]) && $vv->name == $values[$v->id]->value? "selected" : "");?> value="<?=$vv->name;?>"><?=$vv->name;?></option>
                    <? endforeach;?>
                </select>
            <? else:?>
            <input type="text" class="form-control" name="props[<?=$v->id;?>][value]" value="<?=(isset($values[$v->id]) ? $values[$v->id]->value : "");?>">
            <? endif;?>
        </div>

        <div class="form-group col-xs-6" style="margin-top: 30px;">
            <input type="hidden" name="props[<?=$v->id;?>][show]" value="0">
            <label><input type="checkbox" name="props[<?=$v->id;?>][show]" value="1" <?=(isset($values[$v->id]) && $values[$v->id]->show ? "checked" : "");?>> Отображать на листинге</label>
        </div>
    </div>


<? endforeach;?>