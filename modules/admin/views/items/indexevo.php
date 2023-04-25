<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<? if(isset($category->id)):?><h2><?=$category->name;?></h2><? endif;?>

<div class="box">
    <div class="items-index">

        <div class="box-header">
            <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>

            <?= Html::a('Экспорт', ['export'], ['class' => 'btn btn-default']) ?>
            <?= Html::a('Импорт', ['import'], ['class' => 'btn btn-default']) ?>
            <?= Html::a('Изменение цен', ['prices'], ['class' => 'btn btn-default']) ?>
        </div>

        <?//=$this->render('/components/system/_search', ['model' => $searchModel]); ?>

        <?php Pjax::begin([
            'id' => 'indexGrid',
            'enablePushState' => true,
        ]); ?>

        <div class="box-body">
            <?= $this->render("/components/system/_viewGrid", ['view' => $view, 'viewGrid' => [1 => ['class' => 'fa fa-folder'], 2 => ['class' => 'fa fa-bars']]]) ?>

            <? if(isset($category->id)):?>
                <a href="/admin/items/index?category_id=<?=$category->parent;?>" class="btn btn-default"><i class="fa fa-back"></i> Вернуть назад</a>
            <? endif;?>
            <hr>

            <? if($categories):?>
                <? foreach ($categories as $k=>$v):?>
                    <div class="box box-default box-solid collapsed-box loadItemsContainer">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <? if($v->subs):?><a href="/admin/items/index?category_id=<?=$v->id;?>"><? endif;?>
                                    <i class="fa fa-folder"></i> <?=$v->name;?>
                                <? if($v->subs):?></a><? endif;?>
                            </h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool loadItems" data-category_id="<?=$v->id;?>" data-widget="collapse"><i class="fa fa-fw fa-cart-plus"></i> Показать товары</button>
                            </div>
                        </div>
                        <div class="box-body loadItemsBlock" style="display: none;"></div>
                    </div>
                <? endforeach;?>
            <? endif;?>

        <?php Pjax::end(); ?>
    </div>
</div>