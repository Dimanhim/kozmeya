<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ($model->isNewRecord ? "Добавить запись" : ['label' => "ID".$model->id, 'url' => ['update', 'id' => $model->id]]);
if(!$model->isNewRecord) $this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="items-update">

    <h1><?=($model->isNewRecord ? "Добавить запись" : Html::encode("ID".$model->id));?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
