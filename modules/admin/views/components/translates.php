<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<? if(count(\Yii::$app->params["langs"]) > 0 && (!isset($model->disableTranslates) || !$model->disableTranslates)):?>
    <div class="box box-default collapsed-box">
        <div class="box-header with-border">
            <h5 class="box-title">Локализация</h5>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="display: none;">
            <? foreach (\Yii::$app->params["langs"] as $k=>$v):?>
                <?= Html::label($v->name) ?>
                <? if($fieldData["type"] == "textInput"):?>
                    <?= Html::input('text', 'Translates['.$v->id.']['.$field.']', \Yii::$app->langs->modelt($model, $field, $v->code, true), ['class' => 'form-control']) ?>
                <? else:?>
                    <?= Html::textarea('Translates['.$v->id.']['.$field.']', \Yii::$app->langs->modelt($model, $field, $v->code, true), ['class' => '_editor', 'id' => 'Translates_'.$v->id.'_'.$field."_editor"]) ?>
                <? endif;?>
            <? endforeach;?>
        </div>
        <!-- /.box-body -->
    </div>
<? endif;?>