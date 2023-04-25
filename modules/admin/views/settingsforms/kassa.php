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

        <div class="form-group">
            <?= Html::label('Использовать Яндекс.Касса') ?>
            <?= Html::radio('FormData[service]', (isset($data->service) && $data->service == "yandex" ? true : false), ['value' => 'yandex', 'class' => 'radio']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Статус для успешной оплаты') ?>
            <?= Html::dropDownList('FormData[order_success_status]', (isset($data->order_success_status) ? $data->order_success_status : null), yii\helpers\ArrayHelper::map(\app\models\OrdersStatuses::find()->orderBy("name DESC")->all(), 'id', 'name'), ['class' => 'form-control']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('shopId') ?>
            <?= Html::input('text', 'FormData[config][shopId]', (isset($data->config->shopId) ? $data->config->shopId : ""), ['class' => 'form-control']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Scid') ?>
            <?= Html::input('text', 'FormData[config][scid]', (isset($data->config->scid) ? $data->config->scid : ""), ['class' => 'form-control']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Пароль') ?>
            <?= Html::input('text', 'FormData[config][password]', (isset($data->config->password) ? $data->config->password : ""), ['class' => 'form-control']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Производить оплату сразу после оформления заказа') ?>
            <?= Html::hiddenInput('FormData[redirecttopayment]', 0) ?>
            <?= Html::checkbox('FormData[redirecttopayment]', (isset($data->redirecttopayment) && $data->redirecttopayment == 1 ? true : false), ['value' => 1, 'class' => 'checkbox']) ?>
        </div>


        <?php echo $this->render('/components/alert', ['title' => 'Подсказка', 'text' => 'Ознакомится с подробной информацией можно здесь - https://kassa.yandex.ru']); ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
