<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Импорт';
?>
<div class="items-update">

    <h1>Импорт</h1>

    <? if(isset($_GET['import_file'])):?>
        <? if(isset($_GET['msg'])) foreach($_GET['msg'] as $msg):?>
            <div style="color: <? if($msg['type'] == 'error') echo 'red'; else echo 'green';?>;"><?=$msg['value'];?></div>
        <? endforeach;?>

        <div>Обновлено: <?=$_GET['update'];?></div>
        <div>Добавлено: <?=$_GET['insert'];?></div>
    <? endif;?>

    <form action="/admin/items/import" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

        <div class="form-group">
            <label for="" class="control-label">Категория</label>
            <select name="Import[category_id]" class="chosen-select">
                <? foreach(\app\models\Categories::find()->where(["vis" => 1])->orderBy("name ASC")->all() as $k=>$v):?>
                    <option value="<?=$v->id;?>"><?=$v->name;?></option>
                <? endforeach;?>
            </select>

        </div>

        <div class="form-group">
            <label for="" class="control-label">Импорт через файл (xls, xlsx)</label>

                <span class="container_files">
                    <div class="controls cdfiles">
                        <input type="file" name="Import[file]">
                    </div>
                </span>
        </div>
        <button type="submit" class="btn btn-success">Загрузить</button>
        <small>
            <div>* Воспользуйтесь экспортом для определения формата файла</div>
        </small>
    </form>

</div>
