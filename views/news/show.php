<?
use yii\helpers\Html;
?>

<main class="work__area">
    <div class="container">
        <header class="work__area-head">
            <?= $this->render( '/parts/bcrumbs', [] ); ?>

            <div class="tag-elem">Интернет-магазин</div>
            <h1 class="h1-head"><?=Html::encode(\Yii::$app->meta->getPageTitle($item->name));?></h1>
        </header>
        <div class="page-detail-news">
            <article class="news-elem">
                <div class="news-elem-date"><?=\Yii::$app->formatter->asDate($item->date, 'd.MM.yyyy');?></div>
                <div class="news-elem-pic">
                    <img src="<?=\Yii::$app->functions->getUploadItem($item, "images", "ra", "1140x250");?>" alt="<?=$item->name;?>">
                </div>

                <?=$item->text;?>
            </article>
            <div class="clearfix nav-sh">
                <? if($prev):?><div class="fleft"><a href="<?=$prev->url;?>">предыдущая</a></div><? endif;?>
                <? if($next):?><div class="fright"><a href="<?=$next->url;?>">следующая</a></div><? endif;?>
            </div>
        </div>
    </div>

    <? if($also):?>
    <div class="gray__section">
        <div class="container news__obj">
            <div class="tag-elem">Интернет-магазин</div>
            <h3 class="h-3">Новости</h3>
            <div class="row">
                <? foreach($also as $k=>$v):?>
                    <?= $this->render( '/news/parts/item', ['v' => $v, 'class' => 'col-md-4 news-elem-out'] ); ?>
                <? endforeach;?>
            </div>

            <a href="/<?=$page->alias;?>" class="red__btn red__btn-arrow pos-t-r">все новости</a>
        </div>
    </div>
    <? endif;?>
</main>
