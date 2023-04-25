<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Items */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="model-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'autocomplete' => "off"]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'autocomplete' => "off"]) ?>

    <? $types = ['root' => 'ROOT', 'admin' => 'Админ', 'user' => 'Пользователь']; if(!\Yii::$app->user->identity->root) unset($types["root"]);?>
    <?= $form->field($model, 'type')->dropDownList($types, ['class' => 'chosen-select']);?>

    <? $model->password_hash = "";?>
    <?= $form->field($model, 'password_hash')->passwordInput(['autocomplete' => "new-password"]) ?>

    <?= $this->render( '/components/uploader', ['model' => $model, 'form' => $form, 'field' => 'avatar', 'name' => 'avatarUploader'] ); ?>

    <div><a class="btn btn-primary btn-md showMe" data-selector=".showPermissions" data-activetext="Скрыть права доступа" data-text="Редактировать права доступа">Редактировать права доступа</a></div>
    <hr>
    <div class="showPermissions" style="display: none;">
        <legend>Права доступа</legend>
        <? $permissions = ['full' => [], 'view' => []];?>
        <? if($model->permissions) foreach($model->permissions as $k=>$v) $permissions[$v->access][$v->section] = true;?>
        <? foreach(\Yii::$app->params["permissionSections"]  as $section => $name):?>
            <div>
                <h6><a href="<?=Url::toRoute("/admin/".$section);?>"><b><?=ucfirst($name);?></b></a></h6>
                <label>
                    Полный доступ <input class="fullAccess" type="checkbox" <?if(isset($permissions['full'][$section])):?>checked<?endif;?> name="permissions[full][]" value="<?=$section;?>"/>
                </label>
                <label>
                    Просмотр <input type="checkbox" <?if(isset($permissions['view'][$section])):?>checked<?endif;?> name="permissions[view][]" value="<?=$section;?>" />
                </label>
            </div>
        <? endforeach;?>
        <hr>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
