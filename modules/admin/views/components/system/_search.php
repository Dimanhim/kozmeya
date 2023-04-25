<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemsSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<? if(method_exists($model, "searchData")): $fields = $model->searchData(); if(count($fields) > 0):?>
<div class="model-search box-header">
    <legend>Фильтр</legend>

    <div><a class="btn btn-primary btn-md showMe <? if(isset($_GET["filters"])):?>active<? endif;?>" data-selector=".showFilter" data-activetext="Скрыть фильтр" data-text="Показать фильтр"><? if(!isset($_GET["filters"])):?>Показать фильтр<? else:?>Скрыть фильтр<? endif;?></a></div>
    <hr>
    <div class="row showFilter" <? if(!isset($_GET["filters"])):?>style="display: none;"<? endif;?>>
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'class' => 'form-inline'
        ]); ?>

        <? foreach($fields as $field=>$fieldData):?>
            <? if(isset($fieldData["type"]) && $fieldData["type"] == "daterange"):?>
                <div class="form-group col-xs-12">
                    <label><?=$fieldData["label"];?></label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="daterangepicker-input form-control" name="filters[<?=$field;?>]" value="<?=(isset($_GET["filters"][$field]) ? $_GET["filters"][$field] : date("Y-m-d")."/".date("Y-m-d"));?>">
                    </div>
                </div>
            <? elseif(isset($fieldData["type"]) && $fieldData["type"] == "fulltext"):?>
                <div class="form-group col-xs-12">
                    <label><?=$fieldData["label"];?></label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-search"></i>
                        </div>
                        <input class="form-control" name="filters[<?=$field;?>]" value="<?=(isset($_GET["filters"][$field]) ? $_GET["filters"][$field] : "");?>">
                    </div>
                </div>
            <? elseif(isset($fieldData["type"]) && $fieldData["type"] == "between-slider"):?>
                <div class="form-group col-xs-12">
                    <label><?=$fieldData["label"];?></label>
                    <input name="filters[<?=$field;?>]"
                           type="text"
                           value="<?=(isset($_GET['filters'][$field]) ? $_GET['filters'][$field] : $fieldData["min"].",".$fieldData["max"]);?>"
                           class="between-slider form-control"
                           data-slider-min="<?=$fieldData["min"];?>"
                           data-slider-max="<?=$fieldData["max"];?>"
                           data-slider-step="<?=$fieldData["step"];?>"
                           data-slider-value="[<?=(isset($_GET['filters'][$field]) ? $_GET['filters'][$field] : $fieldData["min"].",".$fieldData["max"]);?>]"
                           data-slider-orientation="horizontal" data-slider-selection="before" data-slider-tooltip="show" data-slider-id="aqua"
                           data-value="<?=(isset($_GET['filters'][$field]) ? $_GET['filters'][$field] : $fieldData["min"].",".$fieldData["max"]);?>" style="display: none;">
                </div>
            <? else:?>
                <? if(isset($fieldData["type"]) && $fieldData["type"] == "select"):?>
                <div class="form-group col-xs-3">
                    <label><?=$fieldData["label"];?></label>
                    <select name="filters[<?=$field;?>][]" multiple class="select2" style="width: 100%;">
                        <? if($fieldData["values"]) foreach($fieldData["values"] as $k=>$v):?>
                            <option <?=(isset($_GET["filters"][$field]) && in_array($v->id, $_GET["filters"][$field]) ? "selected" : "");?> value="<?=$v->id;?>"><?=$v->name;?></option>
                        <? endforeach;?>
                    </select>
                </div>
                <? elseif(isset($fieldData["type"]) && $fieldData["type"] == "checkbox"):?>
                    <div class="form-group col-xs-12">
                        <label>
                            <input type="hidden" name="filters[<?=$field;?>]" value="0">
                            <input type="checkbox" class="checkbox" name="filters[<?=$field;?>]" <?=(isset($_GET["filters"][$field]) && $_GET["filters"][$field] == 1 ? "checked" : "");?> value="1"> <?=$fieldData["label"];?>
                        </label>
                    </div>
                <? elseif(isset($fieldData["type"]) && $fieldData["type"] == "radio"):?>
                    <div class="form-group col-xs-12">
                        <label>
                            <input type="hidden" name="filters[<?=$field;?>]" value="0">
                            <input type="radio" class="radio" name="filters[<?=$field;?>]" <?=(isset($_GET["filters"][$field]) && $_GET["filters"][$field] == 1 ? "checked" : "");?> value="1"> <?=$fieldData["label"];?>
                        </label>
                    </div>
                <? else:?>
                    <div class="form-group col-xs-12">
                        <label><?=$fieldData["label"];?></label>

                        <input type="text" class="form-control" name="filters[<?=$field;?>]" value="<?=(isset($_GET["filters"][$field]) ? $_GET["filters"][$field] : "");?>">
                    </div>
                <? endif;?>
            <? endif;?>
        <? endforeach;?>

        <div class="form-group col-xs-12">
            <?= Html::submitButton('Искать', ['class' => 'btn btn-primary']) ?>
            <?= Html::tag('a', 'Сбросить', ['href' => yii\helpers\Url::canonical(), 'class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<hr>
<? endif;?>
<? endif;?>