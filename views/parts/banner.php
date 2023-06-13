<div style="background-image: url('/img/content/banner.jpg');" class="banner__box">
    <div class="container">
        <div class="w-50">
            <div class="b__title-box">
                <div class="banner-title">
                    <?=Yii::$app->langs->t("Доставка дверей бесплатно!")?>*
                </div>
                <small class="banner-title-sm">
                    *<?=Yii::$app->langs->t("Предложение действует при покупке трех и болле дверей")?>
                </small>
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-push-4 col-md-7 col-md-push-5">
                    <div class="banner-loc">
                        <?=Yii::$app->langs->t("Доставляем двери<br>по москве и московской области")?>
                    </div>
                </div>
                <div class="col-lg-4 col-lg-pull-8 col-md-5 col-md-pull-7"><a href="<?=Yii::$app->functions->hierarchyUrl(\Yii::$app->params['allPages'][19]);?>" class="red__btn red__btn-arrow"><?=Yii::$app->langs->t("Подробнее")?>    </a>
                </div>
            </div>
        </div>
    </div>
</div>
