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
            <?= Html::label('Включить отправку sms при изменении статуса заказа') ?>
            <?= Html::hiddenInput('FormData[actives][on_order_status]', 0) ?>
            <?= Html::checkbox('FormData[actives][on_order_status]', (isset($data->actives->on_order_status) && $data->actives->on_order_status == 1 ? true : false), ['value' => 1, 'class' => 'checkbox']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Шаблон (Изменение статуса заказа)') ?>
            <?= Html::input('text', 'FormData[msg][on_order_status]', (isset($data->msg->on_order_status) ? $data->msg->on_order_status : ""), ['class' => 'form-control']) ?>
            <small>
                <strong>{id}</strong> - Номер заказа, <strong>{status_name_old}</strong> - Предыдущий статус заказа, <strong>{status_name_new}</strong> - Новый статус заказа
            </small>
        </div>

        <hr>

        <div class="form-group">
            <?= Html::label('Логин') ?>
            <?= Html::input('text', 'FormData[config][login]', (isset($data->config->login) ? $data->config->login : ""), ['class' => 'form-control']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Пароль') ?>
            <?= Html::input('text', 'FormData[config][password]', (isset($data->config->password) ? $data->config->password : ""), ['class' => 'form-control']) ?>
        </div>

        <?php echo $this->render('/components/alert', ['title' => 'Подсказка', 'text' => 'Смс сервис для отправки сообщений - https://smsc.ru. Необходимо зарегистрироваться и получить все нужные данные']); ?>



        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
