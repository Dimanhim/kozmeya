<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;


$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
if(!$model->isNewRecord) $this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="items-update">

    <h1><?=$model->name;?></h1>


    <div class="model-form">

        <iframe src="/elfinder/elfinder.html" width="100%" height="500px" frameborder="0"></iframe>

    </div>
</div>
