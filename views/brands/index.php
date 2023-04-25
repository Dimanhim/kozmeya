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

        <h1 class="h1-head"><?=Html::encode(\Yii::$app->meta->getPageTitle($page->name));?></h1>
    </header>
    <ul class="list letter-list">
        <li><a href="/<?=$page->alias;?>" class="act">Все</a></li>

        <? if(isset($alphabet["en"])) foreach($alphabet["en"] as $letter => $data):?>
            <li><a href="/<?=$page->alias;?>?filters[letter]=<?=$letter;?>"><?=$letter;?></a></li>
        <? endforeach;?>

        <? if(isset($alphabet["ru"])) foreach($alphabet["ru"] as $letter => $data):?>
            <li><a href="/<?=$page->alias;?>?filters[letter]=<?=$letter;?>"><?=$letter;?></a></li>
        <? endforeach;?>
    </ul>
    <div class="row brand-area">
        <? foreach($dataProvider->getModels() as $k=>$v):?>
        <div class="col-md-3 col-sm-4 col-xs-6 bd-low-xs brand-area-elem">
            <a href="<?=$v->url;?>" class="brand-elem">
                <figure>
                    <div class="brand-i">
                        <img src="<?=\Yii::$app->functions->getUploadItem($v, "images", "ra", "210x110");?>" alt="<?=$v->name;?>">
                    </div>
                    <figcaption class="brand-name"><?=$v->name;?></figcaption>
                </figure>
            </a>
        </div>
        <? endforeach;?>
    </div>

    <div class="gr-line clearfix">
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

    <?=$page->text;?>


    <?=\Yii::$app->meta->getSeoText();?>

    <br>
    <br>
    <?= $this->render( '/catalog/parts/hits', [] ); ?>

</div>

<?= $this->render( '/parts/banner', [] ); ?>
</main>
