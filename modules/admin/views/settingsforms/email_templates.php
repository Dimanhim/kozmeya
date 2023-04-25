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

        <legend>Заказы</legend>
        <div class="form-group">
            <?= Html::label('Новый заказ (Тема)') ?>
            <?= Html::input('text', 'FormData[new_order_subject]', (isset($data->new_order_subject) ? $data->new_order_subject : ""), ['class' => 'form-control']) ?>
            <?= Html::label('Новый заказ (Текст)') ?>
            <?= Html::input('text', 'FormData[new_order]', (isset($data->new_order) ? $data->new_order : ""), ['class' => 'form-control']) ?>
            <small>
                <strong>{id}</strong> - Номер заказа, <strong>{name}</strong> - ФИО заказчика, <strong>{price}</strong> - Итоговая стоимость
            </small>
        </div>

        <div class="form-group">
            <?= Html::label('Изменение статуса заказа заказ (Тема)') ?>
            <?= Html::input('text', 'FormData[on_order_status_subject]', (isset($data->on_order_status_subject) ? $data->on_order_status_subject : ""), ['class' => 'form-control']) ?>
            <?= Html::label('Изменение статуса заказа (Текст)') ?>
            <?= Html::input('text', 'FormData[on_order_status]', (isset($data->on_order_status) ? $data->on_order_status : ""), ['class' => 'form-control']) ?>
            <small>
                <strong>{id}</strong> - Номер заказа, <strong>{status_name_old}</strong> - Предыдущий статус заказа, <strong>{status_name_new}</strong> - Новый статус заказа
            </small>
        </div>

        <hr>
        <legend>Пользователи сайта</legend>
        <div class="form-group">
            <?= Html::label('Новый пользователь (Тема)') ?>
            <?= Html::input('text', 'FormData[new_user_subject]', (isset($data->new_user_subject) ? $data->new_user_subject : ""), ['class' => 'form-control']) ?>
            <?= Html::label('Новый пользователь (Текст)') ?>
            <?= Html::input('text', 'FormData[new_user]', (isset($data->new_user) ? $data->new_user : ""), ['class' => 'form-control']) ?>
            <small>
                <strong>{id}</strong> - ID пользователя, <strong>{name}</strong> - ФИО, <strong>{username}</strong> - Логин, <strong>{password_hash}</strong> - Пароль
            </small>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
