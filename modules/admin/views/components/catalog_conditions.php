<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
?>

<hr>

<legend>Фильтр условий</legend>

<div class="form-group">
    <?= Html::label('Стоимость от') ?>
    <?= Html::input('text', $name.'[price_from]', (isset($data->price_from) ? $data->price_from : ""), ['class' => 'form-control']) ?>
</div>

<div class="form-group">
    <?= Html::label('Стоимость до') ?>
    <?= Html::input('text', $name.'[price_to]', (isset($data->price_to) ? $data->price_to : ""), ['class' => 'form-control']) ?>
</div>

<div class="form-group">
    <?= Html::label('Товары (ID через запятую)') ?>
    <?= Html::input('text', $name.'[items]', (isset($data->items) ? $data->items : ""), ['class' => 'form-control']) ?>
</div>

<div class="form-group">
    <?= Html::label('Категория') ?>

    <?
    $checked = [];
    if(isset($data->categories) && $data->categories != "") {
        foreach($data->categories as $k=>$v){
            $checked[$v] = $v;
        }
    }
    ?>
    <?=$this->render( '/components/recursive_checkbox', ['data' => \app\models\Categories::hierarchy(),  'field' => "name", 'inputname' => $name."[categories]", 'checked' => $checked]);?>
</div>

<div class="form-group">
    <?= Html::label('Бренды') ?>
    <?
    $checked = [];
    if(isset($data->brands) && $data->brands != "") {
        foreach($data->brands as $k=>$v){
            $checked[$v] = $v;
        }
    }
    ?>
    <? foreach(\app\models\Brands::find()->all() as $k=>$v):?>
        <div>
            <label><input type="checkbox" name="<?=$name;?>[brands][<?=$v->id;?>]" value="<?=$v->id;?>" <?=(isset($checked[$v->id]) ? "checked" : "");?>> <?=$v->name;?></label>
        </div>
    <? endforeach;?>
</div>