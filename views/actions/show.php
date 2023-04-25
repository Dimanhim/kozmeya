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
        <div class="detail-shares-area">
            <div style="background-image: url('<?=\Yii::$app->functions->getUploadItem($item, "images", "fx", "1140x248");?>');" class="banner__box">

            </div>

            <?=$item->text;?>
            <div class="clearfix nav-sh">
                <? if($prev):?><div class="fleft"><a href="<?=$prev->url;?>">предыдущая</a></div><? endif;?>
                <? if($next):?><div class="fright"><a href="<?=$next->url;?>">следующая</a></div><? endif;?>
            </div>
        </div>
    </div>

    <?= $this->render( '/parts/call_box', [] ); ?>
</main>