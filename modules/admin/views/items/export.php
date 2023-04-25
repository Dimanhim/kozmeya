<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Экспорт';
?>
<div class="items-update">

    <h1>Экспорт</h1>

    <form action="/admin/items/export" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

        <div class="form-group">
            <label for="" class="control-label">Категория</label>
            <select name="Export[category_id]" class="chosen-select">
                <? foreach(\app\models\Categories::find()->where(["vis" => 1])->orderBy("name ASC")->all() as $k=>$v):?>
                    <option value="<?=$v->id;?>"><?=$v->name;?></option>
                <? endforeach;?>
            </select>

        </div>

        <button type="submit" class="btn btn-success">Экспортировать</button>
    </form>

</div>
