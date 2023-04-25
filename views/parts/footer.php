<div class="footer_form">
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
</div>
<div class="footer_text">
    <div class="row">
        <?=Yii::$app->params["settings"][16];?>
    </div>
</div>
<div class="footer_menu">

    <div class="row">

        <? if(isset(\Yii::$app->params['bottomPagesPid'][0])):?>
            <? foreach(Yii::$app->params['bottomPagesPid'][0] as $k=>$v):?>
                <div class="col-lg-3">
                    <div class="footer-nav">
                        <span class="footer_menu-title"><a href="<?=\Yii::$app->functions->hierarchyUrl($v);?>"><?=$v->name;?></a></span>
                        <? if(isset(\Yii::$app->params['bottomPagesPid'][$v->id])):?>
                            <ul>
                                <? foreach(Yii::$app->params['bottomPagesPid'][$v->id] as $kk=>$vv):?>
                                    <li><a href="<?=\Yii::$app->functions->hierarchyUrl($vv);?>"><?=$vv->name;?></a></li>
                                <? endforeach;?>
                            </ul>
                        <? endif;?>
                    </div>
                </div>
            <? endforeach;?>
        <? endif;?>

        <div class="col-lg-3">
            <span class="footer_menu-title"><?=Yii::$app->langs->t("Контакты");?></span>
            <span class="footer_bold"><?=Yii::$app->langs->t("Адрес шоурума");?>:</span>
            <p><?=Yii::$app->params["settings"][5];?></p>
            <p><?=Yii::$app->langs->t("Предварительная запись по телефону");?>:</p>
            <span class="footer_number"><a href="tel:+<?=\Yii::$app->functions->onlyNumbers(Yii::$app->params["settings"][2]);?>"><?=Yii::$app->params["settings"][2];?></a></span>
            <a class="footer_feedback-call" href="javascript:void(0)" data-toggle="modal" data-target="#feedback-modal"><?=Yii::$app->langs->t("Заказать обратный звонок");?></a>
            <b><?=Yii::$app->langs->t("Почта");?>:</b> <a href="mailto:<?=Yii::$app->params["settings"][8];?>"><?=Yii::$app->params["settings"][8];?></a><br>
            <b><?=Yii::$app->langs->t("Сайт");?>:</b> <a>www.maniamodeler.com</a><br>
        </div>

        <div class="col-lg-3">
            <span class="footer_menu-title"><?=Yii::$app->langs->t("Покупателям");?></span>
            <p><?=Yii::$app->langs->t("Подпишитесь на обновления по электронной почте и будьте в курсе последних новостей о коллекциях и распродажах");?></p>
            <div class="footer_form">
                <form class="js-validate-form form-ajax" method="post" action="">
                    <input type="hidden" name="Форма" value="Подписка на новости">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                    <input type="hidden" name="_subscribe" value="1"/>
                    <input type="hidden" name="_subscribe_model" value="News"/>

                    <div class="input">
                        <input class="required" type="email" id="E-mail" placeholder="<?=Yii::$app->langs->t("Адрес электронной почты");?>" name="E-mail">
                    </div>
                    <div class="button">
                        <button type="submit"  class="sub">></button>
                    </div>
                </form>
            </div>
            <p><?=Yii::$app->langs->t("Следите за обновлениями, скидками и акциями в наших группах в соцсетях");?>:</p>
            <div class="social_icons">
                <?=Yii::$app->params["settings"][9];?>
               <? /* <a style="font-size: 12px;" href="https://www.vikiweb.ru/sozdanie-saitov">создание сайта</a>*/?>
            </div>
        </div>

    </div>
</div>