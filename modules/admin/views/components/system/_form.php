<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

?>

<div class="model-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

    <input type="hidden" name="updatenstay" class="updateNStayInput" value="0">

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <? if(!$model->isNewRecord):?>
            <a class="btn btn-info updateNStay" href="#">Применить</a>
        <? endif;?>
        <? if(!$model->isNewRecord && isset($model->url) && $model->url != ""):?>
            <a class="btn btn-default" href="<?=$model->url;?>">Посмотреть на сайте</a>
        <? endif;?>
    </div>

    <? foreach($model->fieldsData() as $field=>$fieldData): if($fieldData["type"] == "passwordInput") $model->{$field} = "";?>
        <? if($fieldData["type"] == "uploader"):?>
            <?= $this->render( '/components/uploader', ['model' => $model, 'form' => $form, "field" => $field, "name" => $fieldData["data"]["name"]] ); ?>
        <? elseif($fieldData["type"] == "uploader_custom"):?>
            <?= $this->render( '/components/uploader_custom/uploader_custom', ['model' => $model, 'form' => $form, "field" => $field, 'methodSize' => $fieldData["data"]["methodSize"]] ); ?>
        <? elseif($fieldData["type"] == "lines"):?>
            <?= $this->render( '/components/lines', ['model' => $model, 'form' => $form, "field" => $field, "object" => $fieldData["data"]["object"], "fields" => $fieldData["data"]["fields"]] ); ?>
        <? elseif($fieldData["type"] == "latlong"):?>
            <?= $this->render( '/components/latlong', ['model' => $model, 'form' => $form, "field" => $field, 'lat' => $fieldData["data"]["lat"], 'long' => $fieldData["data"]["long"]] ); ?>
        <? elseif($fieldData["type"] == "mindsearch"):?>
            <?= $this->render( '/components/mindsearch', ['model' => $model, 'form' => $form, 'field' => $field, 'modelClass' => $fieldData["data"]["modelClass"], 'fields' => $fieldData["data"]["fields"], 'searchfields' => $fieldData["data"]["searchfields"]] ); ?>
        <? elseif($fieldData["type"] == "catalog_conditions"):?>
            <?=$this->render('/components/catalog_conditions', ['data' => $fieldData["data"], 'name' => $field]);?>
        <? elseif($fieldData["type"] == "recursive_checkbox"):?>
            <legend><?=$fieldData["data"]["label"];?></legend>
            <?
            $checked = [];
            if(!$model->isNewRecord && $model->{$field}) {
                foreach($model->{$field} as $k=>$v){
                    $checked[$v->id] = $v->id;
                }
            }
            ?>
            <?=$this->render( '/components/recursive_checkbox', ['data' => $fieldData["data"]["data"],  'field' => $fieldData["data"]["fieldname"], 'inputname' => $field, 'checked' => $checked]);?>
        <? elseif($fieldData["type"] == "checkboxListExtended"):?>
            <legend><?=$fieldData["data"]["label"];?></legend>
            <?
            $checked = [];
            if(!$model->isNewRecord && $model->{$field}) {
                foreach($model->{$field} as $k=>$v){
                    $checked[$v->id] = $v->id;
                }
            }
            ?>
            <?= Html::checkboxList($field, $checked, $fieldData["data"]["values"]); ?>
            <hr>
        <? elseif($fieldData["type"] == "widget"):?>
            <?= $form->field($model, $field)->{$fieldData["type"]}($fieldData["widget"], $fieldData["data"]) ?>
        <? elseif($fieldData["type"] == "customfields"):?>
            <?=(!$model->isNewRecord ? $this->render( '/components/customfields', ['model' => $model, 'form' => $form, "field" => $field]) : "");?>
        <? else:?>
            <? if($model->isNewRecord && isset($fieldData["defaultIsNew"])) $model->{$field} = $fieldData["defaultIsNew"];?>

            <? if($fieldData["type"] == "dropDownList" && isset($fieldData["prompt"])):?>
                <?=$form->field($model, $field)->{$fieldData["type"]}($fieldData["data"], ['prompt'=>$fieldData["prompt"], 'class' => 'chosen-select']);?>
            <? elseif($fieldData["type"] == "dropDownList" && !isset($fieldData["prompt"])):?>
                <?=$form->field($model, $field)->{$fieldData["type"]}($fieldData["data"], ['class' => 'chosen-select']);?>
            <? else:?>
                <?=($fieldData["type"] == "hiddenInput" ? $form->field($model, $field)->{$fieldData["type"]}($fieldData["data"])->label(false) : $form->field($model, $field)->{$fieldData["type"]}($fieldData["data"]));?>
            <? endif;?>

            <? if(in_array($fieldData["type"], ["textInput", "textArea"]) && !in_array($field, ["vis", "posled", "alias", "id"])):?>
                <?= $this->render( '/components/translates', ['model' => $model, 'form' => $form, "field" => $field, 'fieldData' => $fieldData] ); ?>
            <? endif;?>
        <? endif;?>
    <? endforeach;?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <? if(!$model->isNewRecord):?>
            <a class="btn btn-info updateNStay" href="#">Применить</a>
        <? endif;?>
        <? if(!$model->isNewRecord && isset($model->url) && $model->url != ""):?>
            <a class="btn btn-default" href="<?=$model->url;?>">Посмотреть на сайте</a>
        <? endif;?>
        <? if($model->isNewRecord && isset($model->shareActive) && $model->shareActive):?>
            <div class="form-group">
                <input type="hidden" name="share" value="0">
                <label><input type="checkbox" name="share" value="1"> Опубликовать в соц.сетях</label>
            </div>
        <? endif;?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
