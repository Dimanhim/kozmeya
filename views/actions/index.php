<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\Paginator;
use yii\widgets\Pjax;
?>

<main class="work__area">
    <div class="container">
        <header class="work__area-head">
            <?= $this->render( '/parts/bcrumbs', [] ); ?>
            <div class="tag-elem">Интернет-магазин</div>
            <h1 class="h1-head"><?=Html::encode(\Yii::$app->meta->getPageTitle($page->name));?></h1>
        </header>


        <?php Pjax::begin([
            'id' => 'actionsGrid',
            'enablePushState' => true,
        ]); ?>


        <? foreach($dataProvider->getModels() as $k=>$v):?>
            <?= $this->render( '/actions/parts/item', ['v' => $v] ); ?>
        <? endforeach;?>

        <div class="pagination">
            <?=Paginator::widget([
                'pagination' => $dataProvider->getPagination(),
                'activePageCssClass' => 'act',
                'nextPageCssClass' => 'right-pagi',
                'prevPageCssClass' => 'left-pagi',
                'nextPageLabel' => '&nbsp;',
                'prevPageLabel' => '&nbsp;'
            ]);?>
        </div>

        <?php Pjax::end(); ?>
    </div>

    <?= $this->render( '/parts/call_box', [] ); ?>
</main>