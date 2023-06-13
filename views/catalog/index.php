<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\Paginator;

?>


<div class="top_text-block">
    <div class="container">
        <div class="header_text">
            <h1>
                <? if(isset($tag->id)):?>
                    <?=Html::encode(\Yii::$app->meta->getPageTitle($tag->name));?>
                <? elseif(isset($brand->id)):?>
                    <?=Html::encode(\Yii::$app->meta->getPageTitle($brand->name));?>
                <? elseif(isset($country->id)):?>
                    <?=Html::encode(\Yii::$app->meta->getPageTitle($country->name));?>
                <? elseif(isset($filter->id)):?>
                    <?=Html::encode(\Yii::$app->meta->getPageTitle(($filter->h1 != "" ? $filter->h1 : $filter->name)));?>
                <? elseif($search):?>
                    <?=Html::encode(\Yii::$app->meta->getPageTitle("Поиск '".$query["s"]."'"));?>
                <? elseif($category):?>
                    <?=Html::encode(\Yii::$app->meta->getPageTitle($category->name));?>
                <? else:?>
                    <?=Html::encode(\Yii::$app->meta->getPageTitle($page->name));?>
                <? endif;?>
            </h1>

            <? if($dataProvider->getPagination()->page == 0): ?>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="catalog-top-text">
                            <? if(\Yii::$app->meta->getSeoText() != ""): ?>
                                <?= \Yii::$app->meta->getSeoText(); ?>
                            <? elseif(isset($tag->id)): ?>

                            <? elseif(isset($filter->id)): ?>

                            <? elseif(isset($brand->id)): ?>

                            <? elseif(isset($country->id)): ?>

                            <? elseif($category && $category->text != ""): ?>
                                <?= Yii::$app->langs->modelt($category, 'text'); ?>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <? endif; ?>

        </div>
    </div>

    <? //if(count($dataProvider->getModels()) > 1):?>
        <? if($category):?>
            <?= $this->render('/catalog/parts/filters', [
                'uri' => $uri,
                'surl' => $surl,
                'category' => $category,
                'filters' => $filters,
                'brands' => $brands,
                'query' => $query,
                'gm'    => $dataProvider->getModels(),
                'getParams' => $getParams,
            ] ); ?>
        <? endif;?>
    <?// endif;?>

</div><!--.top_text-block-->

<div class="container">
    <? if(count($dataProvider->getModels()) > 0):?>
    <div class="catalog">
        <div class="models_list">
            <div class="row justify-content-center">
                <? foreach($dataProvider->getModels() as $k=>$v):?>
                    <?php if($v->vis) : ?>
                        <div class="col-6 col-lg-3">
                            <?= $this->render( '/catalog/parts/item', ['v' => $v, 'catalog' => true] ); ?>
                        </div>
                    <?php endif; ?>
                <? endforeach;?>
            </div>
        </div>
    </div>

    <div class="paginator">
        <?=Paginator::widget([
            'pagination' => $dataProvider->getPagination(),
            'activePageCssClass' => 'active',
            'nextPageCssClass' => 'next',
            'prevPageCssClass' => 'prev',
            'nextPageLabel' => '>',
            'prevPageLabel' => '<'
        ]);?>
    </div>
    <? endif;?>
</div>


