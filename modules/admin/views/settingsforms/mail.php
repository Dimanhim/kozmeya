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
            <?= Html::label('Использовать SMTP') ?>
            <?= Html::checkbox('FormData[smtp]', (isset($data->smtp) && $data->smtp ? true : false), ['class' => 'checkbox']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Адрес почтового сервера') ?>
            <?= Html::input('text', 'FormData[host]', (isset($data->host) ? $data->host : ""), ['class' => 'form-control']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Порт') ?>
            <?= Html::input('text', 'FormData[port]', (isset($data->port) ? $data->port : ""), ['class' => 'form-control']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Защита соединения') ?>
            <?= Html::input('text', 'FormData[encryption]', (isset($data->encryption) ? $data->encryption : ""), ['class' => 'form-control']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Логин') ?>
            <?= Html::input('text', 'FormData[username]', (isset($data->username) ? $data->username : ""), ['class' => 'form-control']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Пароль') ?>
            <?= Html::input('text', 'FormData[password]', (isset($data->password) ? $data->password : ""), ['class' => 'form-control']) ?>
        </div>

        <?php echo $this->render('/components/alert', ['title' => 'Подсказка', 'text' => 'SMTP (англ. Simple Mail Transfer Protocol — простой протокол передачи почты) — это широко используемый сетевой протокол, предназначенный для передачи электронной почты в сетях TCP/IP. Рекомендуем использовать Yandex или Google']); ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
