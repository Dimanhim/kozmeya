<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;


$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
if(!$model->isNewRecord) $this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="items-update">

    <h1><?=$model->name;?></h1>


    <div class="model-form">

        <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

        <? if(isset($msg)) foreach ($msg as $k=>$v):?>
        <div class="alert alert-<?=$v["type"];?> alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-<?=($v["type"] == "success" ? "check" : "ban");?>"></i> <?=$v["msg"];?></h4>
            <?=$v["description"];?>
        </div>
        <? endforeach;?>

        <div class="form-group">
            <?= Html::label('Значение изменения цен (+/-)') ?>
            <?= Html::input('text', 'Prices[value]', 0, ['class' => 'form-control col-md-12']) ?>
            <?= Html::dropdownList('Prices[type]', 2, [1 => "%", 2 => "$"], ['class' => '']) ?>
        </div>
        
        <?=$this->render('/components/catalog_conditions', ['data' => [], 'name' => 'Prices']);?>


        <div class="form-group">
            <?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
