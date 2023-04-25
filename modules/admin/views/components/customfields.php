<?
use yii\helpers\Html;
use yii\helpers\Json;

$values = Json::decode($model->{$field}, false);
$customfields = \app\models\CustomFields::find()->where(['class' => \Yii::$app->functions->getModelName($model)])->all();
?>

<? if($customfields):?>
    <legend>Произвольные поля</legend>

    <?foreach($customfields as $customfield):?>
        <div class="form-group">
            <?= Html::label($customfield->name) ?>
            <?= Html::textarea('CustomFields['.$customfield->code.']', (isset($values->{$customfield->code}) ? $values->{$customfield->code} : ""), ['class' => 'form-control']) ?>

            <? if(\Yii::$app->user->identity->root):?>
                <a href="/admin/customfields/update?id=<?=$customfield->id;?>&return_id=<?=$model->id;?>" class="btn btn-default"><i class="fa fa-pencil"></i></a>
                <a href="/admin/customfields/delete?id=<?=$customfield->id;?>&return_id=<?=$model->id;?>" class="btn btn-danger"><i class="fa fa-remove"></i></a>
            <? endif;?>
        </div>
    <? endforeach;?>
<? endif;?>

<? if(\Yii::$app->user->identity->root):?>
    <? if(!$customfields):?>
        <legend>Произвольные поля</legend>
    <? endif;?>

    <a class="btn btn-default" href="/admin/customfields/create?class=<?=\Yii::$app->functions->getModelName($model);?>&return_id=<?=$model->id;?>">Добавить поля</a>
    <hr>
<? endif;?>