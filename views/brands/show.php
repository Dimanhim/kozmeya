<?
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use app\components\Paginator;
?>

<main class="work__area">
<div class="container">
    <header class="work__area-head">
        <?= $this->render( '/parts/bcrumbs', [] ); ?>

        <h1 class="h1-head"><?=Html::encode(\Yii::$app->meta->getPageTitle($item->name));?></h1>
    </header>
    <div class="row detail-br">
        <div class="col-md-3 detail-br-i">
            <img src="<?=\Yii::$app->functions->getUploadItem($item, "images", "ra", "210x110");?>" alt="<?=$item->name;?>">
        </div>
        <div class="col-md-9"><?=$item->text;?></div>
        <div class="col-xs-12">
            <br><a href="/<?=$page->alias;?>" class="red__btn red__btn-arrow-left fright"><?=Yii::$app->langs->t("к списку брендов")?></a>
        </div>
    </div>

    <?php Pjax::begin([
        'id' => 'catalogGrid',
        'enablePushState' => true,
    ]); ?>


    <div class="gr-line clearfix">
        <div class="gr-onleft fleft">
            <?= $this->render('/catalog/parts/sorts', [
                'query' => $query,
                'uri' => $uri,
                'getParams' => $getParams,
            ] ); ?>
        </div>

        <div class="pagination fright">
            <?=Paginator::widget([
                'pagination' => $dataProvider->getPagination(),
                'activePageCssClass' => 'act',
                'nextPageCssClass' => 'right-pagi',
                'prevPageCssClass' => 'left-pagi',
                'nextPageLabel' => '&nbsp;',
                'prevPageLabel' => '&nbsp;'
            ]);?>
        </div>
    </div>

    <div class="row brand-doors">
        <? foreach($dataProvider->getModels() as $k=>$v):?>
            <div class="col-md-2 col-sm-3 col-xs-6 brand-doors-elem">
                <?= $this->render( '/catalog/parts/item', ['v' => $v, 'catalog' => true] ); ?>
            </div>
        <? endforeach;?>
    </div>
    <div class="gr-line clearfix">
        <div class="gr-onleft fleft">
            <div class="gr-title"><?=Yii::$app->langs->t("Показывать на странице")?></div>
            <div class="gr-another">
                <div class="gr-sort">
                    <a href="/<?=$uri;?>?pageSize=20" class="el-count <? if($pageSize == 20):?>act<? endif;?>">20</a>
                    <a href="/<?=$uri;?>?pageSize=40" class="el-count <? if($pageSize == 40):?>act<? endif;?>">40</a>
                    <a href="/<?=$uri;?>?pageSize=100" class="el-count <? if($pageSize == 100):?>act<? endif;?>">100</a>
                </div>
            </div>
        </div>

        <div class="pagination fright">
            <?=Paginator::widget([
                'pagination' => $dataProvider->getPagination(),
                'activePageCssClass' => 'act',
                'nextPageCssClass' => 'right-pagi',
                'prevPageCssClass' => 'left-pagi',
                'nextPageLabel' => '&nbsp;',
                'prevPageLabel' => '&nbsp;'
            ]);?>
        </div>
    </div>
    <?php Pjax::end(); ?>

</div>
<?= $this->render( '/parts/banner', [] ); ?>
</main>
