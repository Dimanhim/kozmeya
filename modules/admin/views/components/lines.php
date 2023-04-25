<? $labels = $model->attributeLabels();?>
<hr>
<legend><?=$labels[$field];?></legend>
<? foreach($fields as $fieldData): if($fieldData["placeholder"] != ""):?>
    <span style="font-weight: bold; size: 20px; width: 150px;display: inline-block;"><?=$fieldData["placeholder"];?></span>
<? endif;endforeach;?>
<div class="<?=$field;?>-edit-container form-inline lines-component">
    <? $objectDatas = (isset($objectData) ? $objectData : $model->{$object});?>
    <? if(isset($objectDatas) && $objectDatas && count($objectDatas)): foreach($objectDatas as $index => $data):?>
        <div class="<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>-edit-row-size lines-component-row">
            <div class="form-group">
                <? foreach($fields as $fieldData):?>
                    <? if($fieldData["type"] == "checkbox" || $fieldData["type"] == "radio"):?>
                        <label><input class="unclear" type="<?=$fieldData["type"];?>" name="<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>[<?=$fieldData["name"];?>][]" value="1" <?=($data->{$fieldData["name"]} == 1 ? "checked" : "");?> /> <?=$fieldData["placeholder"];?></label>
                    <? elseif($fieldData["type"] == "file"):?>
                        <label>
                            <? if($data->{$fieldData["name"]} != ""):?><img class="line-remove" src="<?=\Yii::$app->functions->getUploadItem($data, $fieldData["name"], "fx", "30x30");?>" /><? endif;?>
                            <input type="<?=$fieldData["type"];?>" name="<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>[<?=$fieldData["name"];?>][]" placeholder="<?=$fieldData["placeholder"];?>"/>
                            <input type="hidden" name="<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>[<?=$fieldData["name"];?>_hidden][]" value="<?=$data->{$fieldData["name"]};?>" />
                        </label>
                    <? elseif($fieldData["type"] == "textarea"):?>
                        <textarea name="<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>[<?=$fieldData["name"];?>][]" placeholder="<?=$fieldData["placeholder"];?>"><?=$data->{$fieldData["name"]};?></textarea>
                    <? elseif($fieldData["type"] == "select"):?>
                        <select name="<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>[<?=$fieldData["name"];?>][]" placeholder="<?=$fieldData["placeholder"];?>" class="form-control">
                            <? if(isset($fieldData["values"]) && count($fieldData["values"]) > 0) foreach($fieldData["values"] as $option_value => $option_name):?>
                                <option <?=($data->{$fieldData["name"]} == $option_value ? "selected" : "");?> value="<?=$option_value;?>"><?=$option_name;?></option>
                            <? endforeach;?>
                        </select>
                    <? else:?>
                        <input class="form-control <?=(isset($fieldData["value"]) ? "unclear" : "");?>"" type="<?=$fieldData["type"];?>" name="<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>[<?=$fieldData["name"];?>][]" placeholder="<?=$fieldData["placeholder"];?>" value="<?=$data->{$fieldData["name"]};?>"/>
                    <? endif;?>
                <? endforeach;?>
            </div>

            <? if(true):?><a class="btn btn-danger removeLine"><i class="fa fa-trash"></i></a><? endif;?>
        </div>
    <? endforeach;?>
    <? else:?>
        <div class="<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>-edit-row-size lines-component-row">
            <? foreach($fields as $fieldData):?>
                <? if($fieldData["type"] == "checkbox" || $fieldData["type"] == "radio"):?>
                    <label><input class="unclear" type="<?=$fieldData["type"];?>" name="<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>[<?=$fieldData["name"];?>][]" value="1" checked /> <?=$fieldData["placeholder"];?></label>
                <? elseif($fieldData["type"] == "file"):?>
                    <label>
                        <input type="<?=$fieldData["type"];?>" name="<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>[<?=$fieldData["name"];?>][]" placeholder="<?=$fieldData["placeholder"];?>" />
                        <input type="hidden" name="<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>[<?=$fieldData["name"];?>_hidden][]" value="" />
                    </label>
                <? elseif($fieldData["type"] == "textarea"):?>
                    <textarea name="<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>[<?=$fieldData["name"];?>][]" placeholder="<?=$fieldData["placeholder"];?>"></textarea>
                <? elseif($fieldData["type"] == "select"):?>
                    <select name="<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>[<?=$fieldData["name"];?>][]" placeholder="<?=$fieldData["placeholder"];?>" class="form-control">
                        <? if(isset($fieldData["values"]) && count($fieldData["values"]) > 0) foreach($fieldData["values"] as $option_value => $option_name):?>
                            <option value="<?=$option_value;?>"><?=$option_name;?></option>
                        <? endforeach;?>
                    </select>
                <? else:?>
                    <input class="form-control <?=(isset($fieldData["value"]) ? "unclear" : "");?>" type="<?=$fieldData["type"];?>" name="<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>[<?=$fieldData["name"];?>][]" value="<?=(isset($fieldData["value"]) ? $fieldData["value"] : "");?>" placeholder="<?=$fieldData["placeholder"];?>" />
                <? endif;?>
            <? endforeach;?>
        </div>
    <? endif;?>

    <a data-selector=".<?=$field;?><?=(isset($fieldKey) ? "_".$fieldKey : "");?>-edit-row-size" data-increment="false" class="btn btn-default addLine">Добавить</a>
</div>
<hr>