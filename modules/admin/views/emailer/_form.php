<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Items */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="model-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>


    <?= $form->field($model, 'emails')->textInput() ?>

    <?= $form->field($model, 'files')->fileInput() ?>


    <?= $form->field($model, 'subject')->textInput() ?>

    <?= $form->field($model, 'text')->textArea(['rows' => 6, 'id' => 'editor']) ?>

    <? if(!$model->isNewRecord):?>
        <div><strong>Статус: <?=$model->statuses[$model->status];?></strong></div>
        <div><strong>Последняя рассылка: <?=($model->last_send != "0000-00-00 00:00:00" ? date("d.m.Y H:i:s", strtotime($model->last_send)) : "Еще не выполнялась" );?></strong></div>
    <? endif;?>

    <div class="form-group">
        <? if(!$model->isNewRecord && $model->status == 3):?>
        <a href="/admin/items/update?id=<?=$model->id;?>&status=1" class="btn btn-success">Запустить</a>
        <? endif;?>

        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
