<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model app\models\Items */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="model-form">
    <?php $form = ActiveForm::begin(['options'=>['id' => 'orderForm', 'enctype'=>'multipart/form-data']]); ?>


    <legend>Состав заказ</legend>

    <div>
        <input style="width: 100%;" model="\app\models\Items" fields='<?=Json::encode(["id", "name", "price"]);?>' searchfields='<?=Json::encode(["id", "name"]);?>' placeholder="Начать поиск" class="orderAddItems">
    </div>

    <div class="orderItemsRows">
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th>&nbsp;</th>
                    <th>Кол-во</th>
                    <th>Название</th>
                    <th>Артикул</th>
                    <th>Цена</th>
                    <th>Всего</th>
                    <th>&nbsp;</th>
                </tr>

                <tbody class="orderItems sortable">
                <? if($model->items):?>
                    <?foreach($model->items as $kk=>$vv):?>
                        <?=$this->render("/orders/_items", ['item' => $vv, 'edit' => true]);?>
                    <? endforeach;?>
                <? else:?>
                    <?=$this->render("/orders/_items", ['edit' => true]);?>
                <? endif;?>
                </tbody>
            </table>
        </div>


        <div class="orderPrices">
            <?=$this->render("/orders/_prices", ['model' => $model,]);?>
        </div>
    </div>



    <hr>

    <legend>Данные покупателя</legend>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'class' => 'phone-mask form-control']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textArea(['row' => 6]) ?>

    <hr>

    <legend>Информация о заказе</legend>
    <?=$form->field($model, 'status_id')->dropDownList(ArrayHelper::map(\app\models\OrdersStatuses::find()->orderBy("name DESC")->all(), 'id', 'name'), ['class' => 'chosen-select']);?>

    <?=$form->field($model, 'delivery_id')->dropDownList(ArrayHelper::map(\app\models\Deliveries::find()->orderBy("name DESC")->all(), 'id', 'name'), ['class' => 'chosen-select']);?>

    <?=$form->field($model, 'payment_id')->dropDownList(ArrayHelper::map(\app\models\Payments::find()->orderBy("name DESC")->all(), 'id', 'name'), ['class' => 'chosen-select']);?>

    <?=$form->field($model, 'pickup_point_id')->dropDownList(ArrayHelper::map(\app\models\Pickuppoints::find()->orderBy("name DESC")->all(), 'id', 'name'), ['class' => 'chosen-select']);?>

    <?= $form->field($model, 'discount_value')->textInput(['maxlength' => true, 'class' => 'form-control orderItemsRowChanger']) ?>

    <?= $form->field($model, 'discount_type')->dropDownList([1 => "%", 2 => "$"], ['class' => 'chosen-select orderItemsRowChanger']);?>

    <?= $form->field($model, 'promocode')->textInput(['maxlength' => true, 'class' => 'form-control orderItemsRowChanger']) ?>

    <?= $form->field($model, 'adding_price')->textInput(['maxlength' => true, 'class' => 'form-control orderItemsRowChanger']) ?>

    <?= $form->field($model, 'delivery_price')->textInput(['maxlength' => true, 'class' => 'form-control orderItemsRowChanger']) ?>

    <?= $form->field($model, 'delivery_date')->widget(\yii\jui\DatePicker::classname(), [
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>

    <?= $form->field($model, 'delivery_time')->textInput(['maxlength' => true, 'class' => 'form-control time-mask']) ?>
    <?= $form->field($model, 'delivery_time_range')->textInput(['maxlength' => true, 'class' => 'form-control time-range-mask']) ?>

    <hr>

    <? if(!$model->isNewRecord && $model->history):?>
        <legend>История изменений</legend>
        <table class="table table-bordered">
            <tr>
                <th>Дата</th>
                <th>Пользователь</th>
            </tr>
            <? foreach($model->history as $k=>$v): if($k <= 5):?>
                <tr>
                    <td><?=date("d.m.Y H:i:s", strtotime($v->date));?></td>
                    <td><?=$v->adminuser->username;?></td>
                </tr>
            <? endif;endforeach;?>
        </table>
        <hr>
    <? endif;?>

    <?=$this->render("/orders/_comments", ['model' => $model, 'add' => true, 'collapsed' => false]);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?=(!$model->isNewRecord ? $this->render("/orders/_actions", ['model' => $model,]) : "");?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
