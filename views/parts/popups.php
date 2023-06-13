<?php
$showBanner = false;
$cookies = Yii::$app->request->cookies;
if (!$cookies->has('cgood')) {
    $showBanner = true;
    $cook = Yii::$app->response->cookies;
    $cook->add(new \yii\web\Cookie([
        'name' => 'cgood',
        'value' => 1,
    ]));
}
?>
<?php if($showBanner) : ?>
<div class="cookies-banner">
    <p class="cookies-text">
        <?=Yii::$app->langs->t("Мы используем на своем сайте файлы cookie. Если вы продолжаете использовать сайт, то мы будем считать, что вас это устраивает")?>
    </p>
    <a href="#" class="btn btn-white btn-w-m cgood cgood-btn"  data-dismiss="modal"><?=Yii::$app->langs->t("Хорошо")?></a>
</div>
<?php endif; ?>
<? if (false): ?>
<div id="kuki-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="text-right">
                <span data-dismiss="modal" class="modal-close"></span>
            </div>

            <div class="modal-body">

                <div class="thanks-modal-content">
                  <div class="thanks-modal-text">
                      <?=Yii::$app->langs->t("Мы используем на своем сайте файлы cookie. Если вы продолжаете использовать сайт, то мы будем считать, что вас это устраивает")?>
                  </div>
                </div>

                <div class="text-center">
                  <a href="#" class="btn btn-dark btn-w-m cgood" data-dismiss="modal"><?=Yii::$app->langs->t("Хорошо")?></a>
                </div>

            </div>
        </div>
    </div>
</div>
<? endif; ?>

<div id="feedback-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="margin: 10px auto 0 auto;">
            <div class="text-right">
                <span data-dismiss="modal" class="modal-close"></span>
            </div>

            <div class="modal-body">
                <?= $this->render('_feedback_form') ?>

                <div class="form-error-mess">
                    <?=Yii::$app->langs->t("В одном или нескольких похях есть ошибки. Пожалуйста, проверьте и отправьте сообщение снова")?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="thanks-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="text-right">
                <span data-dismiss="modal" class="modal-close"></span>
            </div>

            <div class="modal-body">

                <div class="thanks-modal-content">
                  <div class="thanks-modal-title"><?=Yii::$app->langs->t("БОЛЬШОЕ СПАСИБО")?>!</div>
                  <div class="thanks-modal-text"><?=Yii::$app->langs->t("НАШ МЕНЕДЖЕР ПЕРЕЗВОНИТ ВАМ В БЛИЖАЙШЕЕ ВРЕМЯ")?></div>
                </div>

                <div class="text-center">
                  <a href="#" class="btn btn-dark btn-w-m" data-dismiss="modal"><?=Yii::$app->langs->t("ЗАКРЫТЬ")?></a>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="black" id="black" style="display: none;"></div>
