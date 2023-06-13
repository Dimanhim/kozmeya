<div class="notify notify--success" id="notify_success">
    <span class="notify__message"></span>
</div>
<div class="notify notify--error" id="notify_error">
    <span class="notify__message"></span>
</div>
<div class="notify notify--warning" id="notify_warning">
    <span class="notify__message"></span>
</div>

<div class="header">
    <div class="top_line">


        <div class="container-fluid">

            <div class="row align-items-center">
                <div class="col-4">

                    <? if (!Yii::$app->params['fcart']): ?>
                        <div class="burger pull-left">
                            <span></span>
                        </div>
                    <? endif; ?>

                    <? //if (!Yii::$app->params['fcart']): ?>
                    <? if (false): ?>
                        <div class="lang_changer d-none d-md-inline-block">
                            <a href="#">
                                <?=Yii::$app->functions->getLang(Yii::$app->params['lang']);?> &nbsp;&nbsp;<i class="sprite sprite-32"></i>
                            </a>
                            <ul>
                                <? foreach (\app\models\Langs::find()->where(['code' => 'en'])->all() as $k=>$v):?>
                                <li <? if(\Yii::$app->params['lang'] == $v->code):?>class="active"<? endif;?>>
                                    <a href="javascript:void(0);" data-lang="<?=$v->code;?>" class="changeLang"><?=Yii::$app->functions->getLang($v->code);?></a>
                                </li>
                                <? endforeach;?>
                            </ul>
                        </div><!--.lang_changer-->
                    <? endif; ?>

                     <? if (Yii::$app->params['fcart']): ?>
                        <a href="/" class="back-to-shop">
                            <span class="d-sm-none">
                                <i class="fa fa-angle-left" aria-hidden="true"></i> <?=Yii::$app->langs->t("магазин")?>
                            </span>
                            <span class="d-none d-sm-inline-block"><?=Yii::$app->langs->t("вернуться в магазин")?></span>
                        </a>
                    <? endif; ?>

                </div>

                <div class="col-4">
                    <div class="logo" onclick="location.href='/'">
                        <img src="/img/logo-white.svg">
                    </div>
                </div>

                <? if (!Yii::$app->params['fcart']): ?>
                <div class="col-4">
                    <div class="d-flex justify-content-center justify-content-lg-end align-items-center">

                        <div class="tel_block  d-none d-md-block">
                            <!--
                            <a href="tel:+<?=\Yii::$app->functions->onlyNumbers(Yii::$app->params["settings"][2]);?>"><?=Yii::$app->params["settings"][2];?></a>
                            -->

                        </div>
                        <div class="top_right-buttons d-flex">

                            <div class="call-search">
                                <span><i class="icon search_icon"></i></span>
                                <div class="search_line" id="search_line">
                                    <div class="search_line-wrap">
                                        <div class="container">

                                            <div class="iSearchBlock">

                                                <form class="js-validate-form" action="<?=\Yii::$app->functions->hierarchyUrl(\Yii::$app->params['allPages'][\Yii::$app->params['catalogPageId']]);?>">
                                                    <div class="search-input">
                                                        <input autocomplete="off" type="search" class="required <? /*iSearch*/?>" placeholder="search" name="s">
                                                        <span class="btn-search"></span>
                                                    </div>


                                                    <!-- <div class="search_close">
                                                        <span class="d-none d-lg-block"><?=Yii::$app->langs->t("Свернуть поиск");?></span>
                                                        <span class="d-lg-none">&times;</span>
                                                    </div> -->

                                                </form>

                                                <? if(isset(\Yii::$app->session["s"]) && count(\Yii::$app->session["s"]) > 0):?>
                                                    <div class="latest_searchs">
                                                        <span><?=Yii::$app->langs->t("Ваши недавние запросы")?>:</span>
                                                        <? foreach (\Yii::$app->session["s"] as $s):?>
                                                            <a href="<?=\Yii::$app->functions->hierarchyUrl(\Yii::$app->params['allPages'][\Yii::$app->params['catalogPageId']]);?>?s=<?=$s;?>"><?=$s;?></a>
                                                        <? endforeach;?>
                                                    </div>
                                                <? endif;?>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--.search_line-->
                            </div><!--.call-search-->

                            <div class="favorites-head call-profile">
                                <span><i class="icon heart_icon"></i></span>
                                <div class="favorites_bar" id="profile_bar">
                                    <div class="container">
                                        <div class="prof">
                                            <div class="row flex-lg-row-reverse">
                                                <div class="col-lg-4 left-separate">
                                                    <div class="auth_block">
                                                        <div class="auth-user d-flex align-items-start flex-column">
                                                            <a href="/profile" class="auth_title-block"><?=Yii::$app->langs->t("Личный кабинет");?></a>
                                                            <ul>
                                                                <? if(Yii::$app->siteuser->identity->orders):?>
                                                                    <li><span><?=Yii::$app->langs->t("Последний заказ");?>:</span><a href="/profile"><?=Yii::$app->siteuser->identity->orders[count(Yii::$app->siteuser->identity->orders)-1]->id;?></a></li>
                                                                <? endif;?>

                                                                <li><span><?=Yii::$app->langs->t("Избранное");?>:</span><a class="favCount" href="/favorites"><?=count(\Yii::$app->params['favorites']);?></a></li>
                                                                <li><span><?=Yii::$app->langs->t("Мои данные");?>:</span><a href="/profile" id="edit_lk-modal"><?=Yii::$app->langs->t("Изменить");?></a></li>
                                                            </ul>
                                                            <div class="auth_footer mt-auto">
                                                                <a class="btn btn-dark" href="/profile"><?=Yii::$app->langs->t("Личный кабинет");?></a>
                                                                <a href="/profile/logout"><?=Yii::$app->langs->t("Выйти");?></a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="d-none d-md-block">
                                                        <span class="imFavorites">
                                                            <?= $this->render('/parts/favorites', [] ); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--.profile_bar-->
                            </div><!--.heart-->

                            <div class="call-profile">
                                <span><i class="cab_icon"></i></span>
                                <div class="profile_bar" id="profile_bar">
                                    <div class="container">
                                        <div class="prof">
                                            <div class="row flex-lg-row-reverse">
                                                <div class="col-lg-4 left-separate">
                                                    <div class="auth_block">
                                                        <? if(Yii::$app->siteuser->isGuest):?>
                                                        <span class="auth_title"><?=Yii::$app->langs->t("Вход");?></span>

                                                        <form id="loginForm">
                                                            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

                                                            <input class="required" type="email" name="SiteLoginForm[username]" placeholder="<?=Yii::$app->langs->t("Адрес электронной почты");?>">
                                                            <input class="required" type="password" name="SiteLoginForm[password]" placeholder="<?=Yii::$app->langs->t("Пароль");?>">

                                                            <div class="row flex-lg-row-reverse">
                                                                <div class="col-lg-7">
                                                                    <div class="mb-4">
                                                                        <button type="submit" class="btn btn-dark btn-block"><?=Yii::$app->langs->t("Войти");?></button>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-5">
                                                                    <a href="#" class="recovery-pass"><?=Yii::$app->langs->t("Забыли пароль?");?></a>
                                                                </div>
                                                            </div>

                                                        </form>

                                                        <div class="auth_block-links">
                                                            <a href="/profile/register"><?=Yii::$app->langs->t("У вас нет учетной записи?");?></a>
                                                            <a href="/profile/register"><?=Yii::$app->langs->t("Регистрация");?></a>
                                                        </div>

                                                        <? else:?>
                                                            <div class="auth-user d-flex align-items-start flex-column">
                                                                <a href="/profile" class="auth_title-block"><?=Yii::$app->langs->t("Личный кабинет");?></a>
                                                                <ul>
                                                                    <? if(Yii::$app->siteuser->identity->orders):?>
                                                                    <li><span><?=Yii::$app->langs->t("Последний заказ")?>:</span><a href="/profile"><?=Yii::$app->siteuser->identity->orders[count(Yii::$app->siteuser->identity->orders)-1]->id;?></a></li>
                                                                    <? endif;?>

                                                                    <li><span><?=Yii::$app->langs->t("Избранное")?>:</span><a class="favCount" href="/favorites"><?=count(\Yii::$app->params['favorites']);?></a></li>
                                                                    <li><span><?=Yii::$app->langs->t("Мои данные")?>:</span><a href="/profile" id="edit_lk-modal"><?=Yii::$app->langs->t("Изменить")?></a></li>
                                                                </ul>

                                                                <div class="auth_footer mt-auto">
                                                                    <a class="btn btn-dark" href="/profile"><?=Yii::$app->langs->t("Личный кабинет")?></a>
                                                                    <a href="/profile/logout"><?=Yii::$app->langs->t("Выйти")?></a>
                                                                </div>
                                                            </div>
                                                        <? endif;?>


                                                    </div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="d-none d-md-block">
                                                        <span class="imFavorites">
                                                            <?//= $this->render('/parts/favorites', [] ); ?>
                                                        </span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div><!--.profile_bar-->
                            </div><!--.call-profile-->

                            <div class="backet-sm">
                                <div class="call-cart">
                                    <!--
                                    <span class="top_span cartCount">
                                        <?//=count(\Yii::$app->params['cart']['items']);?>
                                    </span>
                                    -->
                                    <span class="ico">
                                        <i id="cart_button" class="cart_icon"></i>
                                    </span>
                                </div>
                                <div class="fly-backet imCart" id="fly-backet">
                                    <?= $this->render('/parts/cart', [] ); ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <? endif; ?>

            </div>

        </div>

    </div>
</div><!--.header-->
<?php
/*echo "<pre>";
print_r(Yii::$app->controller->action->id);
echo "</pre>";
exit;*/
?>
<? if (!Yii::$app->params['fcart']): ?>
<div class="top-nav-space">
    <div class="top-nav">
        <div class="container-fluid">
            <div class="mobi-nav">

                <div class="row align-items-center">
                    <div class="col-lg-3 d-none d-lg-block">
                        <a href="/" class="scroll-logo"><img src="/img/logo-white.svg"></a>
                    </div>

                    <div class="col-lg-6">
                        <!--
                        <div class="tel_block d-md-none">
                            <a href="tel:+<?=\Yii::$app->functions->onlyNumbers(Yii::$app->params["settings"][2]);?>"><?=Yii::$app->params["settings"][2];?></a>
                        </div>
                        -->

                        <div class="main_menu">
                            <?= $this->render('/catalog/parts/menu', ['main' => true] ); ?>
                        </div>
                        <div class="text-center">
                            <!--
                            <div class="lang_changer d-md-none">

                                <a href="#">
                                    <?=Yii::$app->functions->getLang(Yii::$app->params['lang']);?> &nbsp;&nbsp;<i class="sprite sprite-32"></i>
                                </a>
                                <ul>
                                    <? foreach (\app\models\Langs::find()->all() as $k=>$v):?>
                                    <li <? if(\Yii::$app->params['lang'] == $v->code):?>class="active"<? endif;?>>
                                        <a href="javascript:void(0);" data-lang="<?=$v->code;?>" class="changeLang"><?=Yii::$app->functions->getLang($v->code);?></a>
                                    </li>
                                    <? endforeach;?>
                                </ul>
                            </div>
                            -->
                            <!--.lang_changer-->
                        </div>
                    </div>

                <? if (!Yii::$app->params['fcart']): ?>
                    <div class="col-3 d-none d-lg-block">

                        <div class="right-panel">
                            <div class="d-flex justify-content-center justify-content-lg-end align-items-center">
                                <div class="tel_block">
                                    <!--
                                    <a href="tel:+<?=\Yii::$app->functions->onlyNumbers(Yii::$app->params["settings"][2]);?>"><?=Yii::$app->params["settings"][2];?></a>
                                    -->
                                </div>
                                <div class="top_right-buttons d-flex">

                                    <div class="call-search">
                                        <span><i class="icon search_icon"></i></span>
                                        <div class="search_line" id="search_line">
                                            <div class="search_line-wrap">
                                                <div class="container">

                                                    <div class="iSearchBlock">
                                                        <form class="js-validate-form" action="<?=\Yii::$app->functions->hierarchyUrl(\Yii::$app->params['allPages'][\Yii::$app->params['catalogPageId']]);?>">
                                                            <div class="search-input">
                                                                <input autocomplete="off" type="search" class="required <? /*iSearch*/?>" placeholder="search" name="s">
                                                                <span class="btn-search"></span>
                                                            </div>
                                                            <!-- <div class="search_close">
                                                                <span class="d-none d-lg-block"><?=Yii::$app->langs->t("Свернуть поиск");?></span>
                                                                <span class="d-lg-none">&times;</span>
                                                            </div> -->

                                                        </form>

                                                        <? if(isset(\Yii::$app->session["s"]) && count(\Yii::$app->session["s"]) > 0):?>
                                                            <div class="latest_searchs">
                                                                <span><?=Yii::$app->langs->t("Ваши недавние запросы")?>:</span>
                                                                <? foreach (\Yii::$app->session["s"] as $s):?>
                                                                    <a href="<?=\Yii::$app->functions->hierarchyUrl(\Yii::$app->params['allPages'][\Yii::$app->params['catalogPageId']]);?>?s=<?=$s;?>"><?=$s;?></a>
                                                                <? endforeach;?>
                                                            </div>
                                                        <? endif;?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!--.search_line-->
                                    </div><!--.call-search-->

                                    <div class="favorites-head call-profile">
                                        <span><i class="icon heart_icon"></i></span>
                                        <div class="favorites_bar" id="profile_bar">
                                            <div class="container">
                                                <div class="prof">
                                                    <div class="row flex-lg-row-reverse">

                                                        <div class="col-lg-4 left-separate">
                                                            <div class="auth_block">


                                                                    <div class="auth-user d-flex align-items-start flex-column">
                                                                        <a href="/profile" class="auth_title-block"><?=Yii::$app->langs->t("Личный кабинет");?></a>
                                                                        <ul>
                                                                            <? if(Yii::$app->siteuser->identity->orders):?>
                                                                                <li><span><?=Yii::$app->langs->t("Последний заказ");?>:</span><a href="/profile"><?=Yii::$app->siteuser->identity->orders[count(Yii::$app->siteuser->identity->orders)-1]->id;?></a></li>
                                                                            <? endif;?>

                                                                            <li><span><?=Yii::$app->langs->t("Избранное");?>:</span><a class="favCount" href="/favorites"><?=count(\Yii::$app->params['favorites']);?></a></li>
                                                                            <li><span><?=Yii::$app->langs->t("Мои данные");?>:</span><a href="/profile" id="edit_lk-modal"><?=Yii::$app->langs->t("Изменить");?></a></li>
                                                                        </ul>
                                                                        <div class="auth_footer mt-auto">
                                                                            <a class="btn btn-dark" href="/profile"><?=Yii::$app->langs->t("Личный кабинет");?></a>
                                                                            <a href="/profile/logout"><?=Yii::$app->langs->t("Выйти");?></a>
                                                                        </div>
                                                                    </div>

                                                            </div>
                                                        </div>

                                                        <div class="col-lg-8">
                                                            <div class="d-none d-md-block">
                                                                <span class="imFavorites">
                                                                    <?= $this->render('/parts/favorites', [] ); ?>
                                                                </span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div><!--.profile_bar-->
                                    </div><!--.heart-->

                                    <div class="call-profile">
                                        <span><i class="cab_icon"></i></span>
                                        <div class="profile_bar" id="profile_bar">
                                            <div class="container">
                                                <div class="prof">
                                                    <div class="row flex-lg-row-reverse">

                                                        <div class="col-lg-4 left-separate">
                                                            <div class="auth_block">

                                                                <? if(Yii::$app->siteuser->isGuest):?>
                                                                <span class="auth_title"><?=Yii::$app->langs->t("Вход");?></span>

                                                                <form id="loginForm">
                                                                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

                                                                    <input class="required" type="email" name="SiteLoginForm[username]" placeholder="<?=Yii::$app->langs->t("Адрес электронной почты");?>">
                                                                    <input class="required" type="password" name="SiteLoginForm[password]" placeholder="<?=Yii::$app->langs->t("Пароль");?>">

                                                                    <div class="row flex-lg-row-reverse">
                                                                        <div class="col-lg-7">
                                                                            <div class="mb-4">
                                                                                <button type="submit" class="btn btn-dark btn-block"><?=Yii::$app->langs->t("Войти");?></button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-5">
                                                                            <a href="#" class="recovery-pass"><?=Yii::$app->langs->t("Забыли пароль?");?></a>
                                                                        </div>
                                                                    </div>

                                                                </form>

                                                                <div class="auth_block-links">
                                                                    <a href="/profile/register"><?=Yii::$app->langs->t("У вас нет учетной записи?");?></a>
                                                                    <a href="/profile/register"><?=Yii::$app->langs->t("Регистрация");?></a>
                                                                </div>

                                                                <? else:?>
                                                                    <div class="auth-user d-flex align-items-start flex-column">
                                                                        <a href="/profile" class="auth_title-block"><?=Yii::$app->langs->t("Личный кабинет");?></a>
                                                                        <ul>
                                                                            <? if(Yii::$app->siteuser->identity->orders):?>
                                                                            <li><span><?=Yii::$app->langs->t("Последний заказ");?>:</span><a href="/profile"><?=Yii::$app->siteuser->identity->orders[count(Yii::$app->siteuser->identity->orders)-1]->id;?></a></li>
                                                                            <? endif;?>

                                                                            <li><span><?=Yii::$app->langs->t("Избранное");?>:</span><a class="favCount" href="/favorites"><?=count(\Yii::$app->params['favorites']);?></a></li>
                                                                            <li><span><?=Yii::$app->langs->t("Мои данные");?>:</span><a href="/profile" id="edit_lk-modal"><?=Yii::$app->langs->t("Изменить");?></a></li>
                                                                        </ul>
                                                                        <div class="auth_footer mt-auto">
                                                                            <a class="btn btn-dark" href="/profile"><?=Yii::$app->langs->t("Личный кабинет");?></a>
                                                                            <a href="/profile/logout"><?=Yii::$app->langs->t("Выйти");?></a>
                                                                        </div>
                                                                    </div>
                                                                <? endif;?>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-8">
                                                            <div class="d-none d-md-block">
                                                                <span class="imFavorites">
                                                                    <?= $this->render('/parts/favorites', [] ); ?>
                                                                </span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div><!--.profile_bar-->
                                    </div><!--.call-profile-->

                                    <div class="backet-sm">
                                        <div class="call-cart">
                                            <!--
                                            <span class="top_span cartCount">
                                                <?//=count(\Yii::$app->params['cart']['items']);?>
                                            </span>
                                            -->
                                            <span class="ico">
                                                <i id="cart_button" class="cart_icon"></i>
                                            </span>
                                        </div>
                                        <div class="fly-backet" id="fly-backet">
                                            <?= $this->render('/parts/cart', [] ); ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                <? endif; ?>

                </div>

            </div>
        </div>

    </div><!--.top-nav-->
</div>
<? endif; ?>

