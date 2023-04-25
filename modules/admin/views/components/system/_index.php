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

<div class="box">
    <div class="items-index">

        <div class="box-header">
            <? if(!isset($hideAdd) || !$hideAdd):?>
            <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
            <? endif;?>

            <? if(isset($buttons) && is_array($buttons)):?>
                <? foreach($buttons as $button):?>
                    <?= Html::a($button["name"], [$button["href"]], ['class' => $button["class"]]) ?>
                <? endforeach;?>
            <? endif;?>
        </div>

        <?=$this->render('/components/system/_search', ['model' => $searchModel]); ?>


        <?php Pjax::begin([
            'id' => 'indexGrid',
            'enablePushState' => true,
        ]); ?>

        <div class="box-body">
            <? if(isset($partials) && is_array($partials)):?>
                <? foreach($partials as $partialView => $partialData):?>
                    <?= $this->render($partialView, $partialData) ?>
                <? endforeach;?>
            <? endif;?>

            <? if(isset($viewGrid) && is_array($viewGrid)):?>
                <?= $this->render("_viewGrid", ['viewGrid' => $viewGrid, 'view' => $view]) ?>
            <? endif;?>

            <form class="GridViewForm">
                <div class="table-responsive">
                    <input type="hidden" name="model" value="<?=str_replace(["search\\", "Search"], "", get_class($searchModel));?>">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => $searchModel->columnsData(),
                        'tableOptions' => [
                            'class' => 'table table-striped table-bordered table-hover'
                        ],

                    ]); ?>
                </div>


                <div class="gridViewActions" style="display: none;">
                    <a data-confirm="Вы уверены, что хотите удалить этот элемент?" data-pjax="1" class="btn btn-danger removeAllRows"><i class="fa fa-remove"></i></a>
                </div>
            </form>
        </div>



        <?php Pjax::end(); ?>
    </div>
</div>