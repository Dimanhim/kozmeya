
<div class="footer_form">
    <?php if(false) : ?>
    <div class="form">
        <span><?=Yii::$app->langs->t("Подпишитесь на обновления по электронной почте и будьте в курсе последних новостей о коллекциях и распродажах");?></span>
        <form class="js-validate-form form-ajax" method="post" action="">
            <input type="hidden" name="Форма" value="Подписка на новости">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
            <input type="hidden" name="_subscribe" value="1"/>
            <input type="hidden" name="_subscribe_model" value="News"/>

            <div class="input">
                <input class="required" type="email" id="E-mail" placeholder="<?=Yii::$app->langs->t("Адрес электронной почты");?>" name="E-mail">
            </div>
            <div class="button">
                <button type="submit" class="sub">></button>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>

<div class="footer_text">
    <div class="row">
        <?=Yii::$app->params["settings"][16];?>
    </div>
</div>
<?= $this->render('_footer_menu') ?>
