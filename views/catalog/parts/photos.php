<? $photos = \Yii::$app->functions->getUploadItems($item, "images");?>

<div class="d-none d-md-block">
    <div class="row">

        <div class="col-md-2 offset-md-2">
            <div class="photos-arrows">
                <span class="photos-arrow photos-prev"></span>
                <div class="photos-nav swiper-container">
                    <div class="swiper-wrapper">
                        <? foreach ($photos as $index => $photo):?>
                        <div class="swiper-slide">
                            <img src="<?=\Yii::$app->functions->setPhoto($photo, "ra", "118x176");?>" alt="<?=$item->name;?>" class="w-100">
                        </div>
                        <? endforeach;?>
                    </div>
                </div>
                <span class="photos-arrow photos-next"></span>
            </div>
        </div>

        <div class="col-md-8">
            <div class="photos-slider swiper-container">
                <div class="swiper-wrapper">
                <? foreach ($photos as $index => $photo):?>
                    <div class="swiper-slide">
                        <img src="<?=\Yii::$app->functions->setPhotoMain($photo, "ra", "565x848");?>" alt="<?=$item->name;?>"  class="w-100">
                    </div>
                    <? endforeach;?>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="d-md-none">
    <div class="mobile_product-image text-center">
        <img src="<?=\Yii::$app->functions->getUploadItem($item, "images", "ra", "565x848");?>" class="img-fluid">
    </div>
</div>
