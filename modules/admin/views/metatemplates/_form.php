<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Items */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="model-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'model')->dropDownList($model->getMetaModels());?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textArea(['rows' => 6]) ?>
    <?= $form->field($model, 'keywords')->textArea(['rows' => 6]) ?>

    <? if($model->isNewRecord) $model->active = 1;?>
    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<legend>Допустимые теги</legend>
<pre>
    <div>{h1} - H1 из сео модуля</div>
    <div>{любое_поле_таблицы}, например {name}</div>
    <div>{любое_поле_таблицы--lc} - Слово в нижнем регистре, например {name--lc}</div>
    <div>{любое_поле_таблицы--s:ПЕРВАЯ_БУКВА_ПАДЕЖА} - Склонение слов, к примеру {name--s:Р} - родительский падеж</div>
    <div>{любое_поле_таблицы--lc--s:ПЕРВАЯ_БУКВА_ПАДЕЖА} - Склонение + регистр</div>
</pre>
