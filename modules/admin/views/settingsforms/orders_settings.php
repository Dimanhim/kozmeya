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

        <legend>Уведомления в Telegram</legend>

        <div class="form-group">
            <?= Html::label('Использовать уведомления в Telegram') ?>
            <?= Html::checkbox('FormData[send_by_telegram]', (isset($data->send_by_telegram) && $data->send_by_telegram ? true : false)) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Telegram Bot token') ?>
            <?= Html::input('text', 'FormData[telegram_bot_token]', (isset($data->telegram_bot_token) ? $data->telegram_bot_token : ""), ['class' => 'form-control']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Telegram ID чата') ?>
            <?= Html::input('text', 'FormData[telegram_chat_id]', (isset($data->telegram_chat_id) ? $data->telegram_chat_id : ""), ['class' => 'form-control']) ?>
        </div>

        <?php echo $this->render('/components/alert', ['title' => 'Подсказка', 'text' => 'Сообщения о новых заказах будут приходить в Вашу группу Telegram']); ?>

        <hr>


        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
