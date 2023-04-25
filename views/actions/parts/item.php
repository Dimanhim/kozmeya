<div class="shares__contain">
    <div style="background-image: url('<?=\Yii::$app->functions->getUploadItem($v, "images", "fx", "1140x248");?>');" class="banner__box">

    </div>
    <div class="info__shares-contain"><b><?=$v->name;?></b>
        <p><?=$v->small;?></p>
        <a href="<?=$v->url;?>" class="red__btn red__btn-arrow">Подробнее  </a>
    </div>
</div>