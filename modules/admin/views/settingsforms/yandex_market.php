<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;


$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
if(!$model->isNewRecord) $this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="items-update">

    <h1><?=$model->name;?></h1>


    <div class="model-form">

        <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

        <div class="form-group">
            <?= Html::label('Ссылка на YML') ?>
            <div>
                <a target="_blank" href="/yml/yandex_market.xml" class="btn btn-default">Открыть</a>
            </div>
        </div>

        <?=$this->render('/components/catalog_conditions', ['data' => $data, 'name' => 'FormData']);?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
