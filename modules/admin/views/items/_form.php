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

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li <? if(!isset($_GET["tab"]) || $_GET["tab"] == "mains"):?>class="active"<? endif;?>><a href="#mains" data-toggle="tab" aria-expanded="true">Базовая информация</a></li>
            <li <? if(isset($_GET["tab"]) && $_GET["tab"] == "categories"):?>class="active"<? endif;?>><a href="#categories" data-toggle="tab" aria-expanded="false">Категории</a></li>
            <li <? if(isset($_GET["tab"]) && $_GET["tab"] == "props"):?>class="active"<? endif;?>><a href="#props" data-toggle="tab" aria-expanded="false">Характеристики</a></li>
            <? /*<li <? if(isset($_GET["tab"]) && $_GET["tab"] == "vars"):?>class="active"<? endif;?>><a href="#vars" data-toggle="tab" aria-expanded="false">Модификации</a></li> */ ?>
        </ul>
        <div class="tab-content">
            <div class="tab-pane <? if(!isset($_GET["tab"]) || $_GET["tab"] == "mains"):?>active<? endif;?>" id="mains">
                <?= $this->render( '/components/mindsearch', ['model' => $model, 'form' => $form, 'modelClass' => get_class($model), 'field' => 'parent', 'fields' => ["id", "name"], 'searchfields' => ["name"]] ); ?>

                <? if($model->subs):?>
                    <legend>Привязанные товары</legend>
                    <? foreach($model->subs as $k=>$v):?>
                        <div><a href="?id=<?=$v->id;?>"><?=$v->name;?></a></div>
                    <? endforeach;?>
                    <hr>
                <? elseif($model->parent != 0):?>
                    <div><a href="?id=<?=$model->parent;?>">Перейти к родительскому товару</a></div>
                    <hr>
                <? endif;?>

                <?=$form->field($model, 'brand_id')->dropDownList(yii\helpers\ArrayHelper::map(\app\models\Brands::find()->orderBy("name DESC")->all(), 'id', 'name'), ['class' => 'chosen-select']);?>

                <?=$form->field($model, 'status_id')->dropDownList(yii\helpers\ArrayHelper::map(\app\models\ItemsStatus::find()->orderBy("name DESC")->all(), 'id', 'name'), ['class' => 'chosen-select']);?>


                <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

                <?= $this->render( '/components/translates', ['model' => $model, 'form' => $form, "field" => "name", 'fieldData' => ["type" => "textInput"]] ); ?>

                <?= $form->field($model, 'alias')->textInput(['maxlength' => true, 'class' => 'form-control imNameAlias']) ?>

                <?=$form->field($model, 'currency_id')->dropDownList(yii\helpers\ArrayHelper::map(\app\models\Currency::find()->orderBy("name DESC")->all(), 'id', 'name'), ['class' => 'chosen-select']);?>

                <?= $form->field($model, 'price')->textInput() ?>

                <?= $form->field($model, 'old_price')->textInput() ?>

                <?//= $form->field($model, 'old_price')->textInput() ?>

                <?= $form->field($model, 'weight')->textInput() ?>

                <?=$form->field($model, 'box_type')->dropDownList([1 => "Маленькая", 2 => "Большая"]);?>

                <?= $form->field($model, 'box_weight')->textInput() ?>

                <?//=$form->field($model, 'rating')->dropDownList([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5]);?>

                <? /*
                <?= $form->field($model, 'date')->widget(\yii\jui\DatePicker::classname(), [
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                ]) ?>
                */ ?>

                <?= $form->field($model, 'text')->textArea(['rows' => 6, 'id' => 'editor']) ?>

                <?= $this->render( '/components/translates', ['model' => $model, 'form' => $form, "field" => "text", 'fieldData' => ["type" => "textArea"]] ); ?>

                <?//= $this->render( '/components/uploader', ['model' => $model, 'form' => $form, 'field' => 'images', 'name' => 'imagesUploader'] ); ?>
                <?= $this->render( '/components/uploader_custom/uploader_custom', ['model' => $model, 'form' => $form, 'field' => 'images', 'methodSize' => 'ra/250x250/'] ); ?>


                <legend>Размеры</legend>
                <?
                $checked = [];
                if(!$model->isNewRecord && $model->sizes) {
                    foreach($model->sizes as $k=>$v){
                        $checked[$v->id] = $v->id;
                    }
                }
                ?>
                <?= Html::checkboxList("sizes", $checked, yii\helpers\ArrayHelper::map(\app\models\Sizes::find()->where("vis = 1")->orderBy("posled")->all(), "id", "name")); ?>
                <hr>

                <legend>Цвета</legend>
                <?
                $checked = [];
                if(!$model->isNewRecord && $model->colors) {
                    foreach($model->colors as $k=>$v){
                        $checked[$v->id] = $v->id;
                    }
                }
                ?>
                <?= Html::checkboxList("colors", $checked, yii\helpers\ArrayHelper::map(\app\models\Colors::find()->where("vis = 1")->orderBy("posled")->all(), "id", "name")); ?>
                <hr>

                <? if($model->isNewRecord) $model->special = 0;?>
                <?= $form->field($model, 'special')->checkbox() ?>

                <? if($model->isNewRecord) $model->vis = 1;?>
                <?= $form->field($model, 'vis')->checkbox() ?>

                <? if($model->isNewRecord) $model->posled = 999;?>
                <?= $form->field($model, 'posled')->textInput() ?>

                <?=$this->render( '/components/customfields', ['model' => $model, 'form' => $form, "field" => "_customfields"]);?>
            </div>

            <div class="tab-pane <? if(isset($_GET["tab"]) && $_GET["tab"] == "categories"):?>active<? endif;?>" id="categories">
                <legend>Категории</legend>
                <?
                $categories = \app\models\Categories::hierarchy();
                $checked = [];
                if(!$model->isNewRecord && $model->categories) {
                    foreach($model->categories as $k=>$v){
                        $checked[$v->id] = $v->id;
                    }
                }
                ?>
                <?= $this->render( '/components/recursive_checkbox', ['data' => $categories,  'field' => 'name', 'inputname' => 'categories', 'checked' => $checked]); ?>
            </div>
            <div class="tab-pane <? if(isset($_GET["tab"]) && $_GET["tab"] == "props"):?>active<? endif;?>" id="props">
                <legend>Характеристики</legend>

                <?= $this->render( '/components/props', ['model' => $model, 'form' => $form] ); ?>
            </div>
            <? /*
            <div class="tab-pane <? if(isset($_GET["tab"]) && $_GET["tab"] == "vars"):?>active<? endif;?>" id="vars">
                <legend>Модификации</legend>

                <? if(!$model->isNewRecord):?>
                    <? if($model->vars)  foreach($model->vars as $k=>$v):?>
                        <h4><?=$v->name;?> <a href="/admin/itemsvars/update?id=<?=$v->id;?>"><i class="fa fa-pencil"></i></a><a href="/admin/itemsvars/delete?id=<?=$v->id;?>"><i class="fa fa-remove"></i></a></h4>
                        <table class="table table-bordered table-responsive">
                            <tr>
                                <th>Название</th>
                                <th>Цена</th>
                                <th>Изображение</th>
                                <th>Показывать</th>
                                <th>Сортировка</th>
                                <th>&nbsp;</th>
                            </tr>

                            <? if($v->values) foreach($v->values as $kk=>$vv):?>
                                <tr>
                                    <td><?=($vv->value ? $vv->value->name : $vv->name);?></td>
                                    <td><?=$vv->price;?></td>
                                    <td>
                                        <? if($vv->value):?>
                                            <? if($vv->value->files != ""):?>
                                                <img src="<?=\Yii::$app->functions->getUploadItem($vv->value, "files", "fx", "30x30");?>" />
                                            <? endif;?>
                                        <? endif;?>

                                        <? if($vv->images != ""):?>
                                            <img src="<?=\Yii::$app->functions->getUploadItem($vv, "images", "fx", "30x30");?>" />
                                        <? endif;?>
                                    </td>
                                    <td><?=($vv->vis ? "<i class='fa fa-check'></i>" : "<i class='fa fa-close'></i>");?></td>
                                    <td><?=$vv->posled;?></td>
                                    <td>
                                        <a href="/admin/itemsvars/update?id=<?=$v->id;?>"><i class="fa fa-pencil"></i></a>
                                    </td>
                                </tr>
                            <? endforeach;?>
                        </table>

                    <? endforeach;?>
                    <hr>
                    <a href="/admin/itemsvars/create?item_id=<?=$model->id;?>" class="btn btn-success">Добавить</a>
                <? else:?>
                    <p>Чтобы добавлять модификации необходимо сохранить запись</p>
                <? endif;?>
            </div>
            <!-- /.tab-pane -->
            */ ?>
        </div>
        <!-- /.tab-content -->
    </div>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : (isset($_GET["cloneid"]) ? 'Клонировать' : 'Обновить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <? if(!$model->isNewRecord && !isset($_GET["cloneid"])):?>
        <?= Html::tag('a', 'Клонировать', ['href' => Url::toRoute(["items/create", "cloneid" => $model->id]), 'class' => 'btn btn-default']) ?>
        <? endif;?>

        <? if(!$model->isNewRecord && isset($model->url) && $model->url != ""):?>
            <a class="btn btn-default" href="<?=$model->url;?>">Посмотреть на сайте</a>
        <? endif;?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
