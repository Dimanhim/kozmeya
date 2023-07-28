<div class="d-md-none">
    <!--<div class="d-none d-md-block">-->
    <div class="row">

        <div class="col-sm-12">
            <div class="photos-slide swiper-container">
                <div class="slick-product">
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
<style>

</style>
