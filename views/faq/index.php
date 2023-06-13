<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\Paginator;
use yii\widgets\Pjax;
?>

<main class="main">
    <div class="page-top page-top--about">
        <div class="page-top__inner">
            <div class="features">
                <div class="container">
                    <?=\Yii::$app->params['settings'][9];?>
                </div>
            </div>

            <div class="breadcrumbs-wrap">
                <div class="container">
                    <?= $this->render( '/parts/bcrumbs', [] ); ?>
                </div>
            </div>

            <div class="container hidden-xs">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="about-page-title">
                            <span class="about-page-title__top"><?=Yii::$app->langs->t("Надежность, проверенная<br> десятилетиями!")?></span><br>
                            <div class="about-page-title__main">
                                <ul>
                                    <li><?=Yii::$app->langs->t("Один из лидеров  среди риэлторских компаний Москвы")?></li>
                                    <li><?=Yii::$app->langs->t("Полный комплекс услуг")?></li>
                                    <li><?=Yii::$app->langs->t("Удобное расположение офисов")?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-5 col-lg-5">
                        <div class="page-top-form mb--30">
                            <div class="page-top-form__title">Есть вопросы?</div>
                            <form class="form-ajax js-validate" data-thank="Спасибо, мы вскоре свяжемся с вами!">
                                <input type="hidden" name="Форма" value="Есть вопросы">
                                <label class="ib mb--20">
                                    <input class="input input--dark" type="text" name="Имя" placeholder="Ваше имя">
                                </label>
                                <label class="ib mb--20">
                                    <input class="input input--dark required" type="email" name="Почта" placeholder="Ваша почта">
                                </label>
                                <label class="ib mb--30">
                                    <input class="input input--dark required requiredphone" type="tel" name="Телефон" placeholder="Ваш телефон">
                                </label>
                                <div class="t--center">
                                    <button class="btn btn--geom-big btn btn--geom-w190 btn--theme-yellow" type="submit">Отправить</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="page-main page-main--about">
        <div class="container">

            <div class="p-nav-line mb--30">
                <?= $this->render( '/parts/left_menu', ['page' => $page] ); ?>
            </div>

            <div class="faq">

                <?php Pjax::begin([
                    'id' => 'faqGrid',
                    'enablePushState' => true,
                ]); ?>

                <div class="accordion-container">
                    <ul class="accordion js-faq-accordion">


                        <? foreach($dataProvider->getModels() as $k=>$v):?>
                        <li class="accordion__item js-acc-item">
                            <div class="accordion__item-title js-acc-item-title"><i class="accordion__item-icon js-acc-item-icon"></i><?=$v->name;?></div>
                            <div class="accordion__item-content text js-acc-item-box"><?=$v->text;?></div>
                        </li>

                        <? endforeach;?>

                    </ul>
                </div>

                <div class="pagination-wrap mb--60">
                    <?=Paginator::widget([
                        'pagination' => $dataProvider->getPagination(),
                        'activePageCssClass' => 'current',
                        'nextPageCssClass' => 'next',
                        'prevPageCssClass' => 'prev',
                        'nextPageLabel' => '&nbsp;',
                        'prevPageLabel' => '&nbsp;'
                    ]);?>
                </div>

                <?php Pjax::end(); ?>

            </div>

            <hr class="hr--grey mb--40">
            <div class="page-title t--center">Не нашли ответ на свой вопрос? Напишите нам!</div>
            <p class="t--center m--0 mb--30">Квалифицированные специалисты дадут развернутый ответ на любой интересующий вас вопрос</p>

            <div class="p-question-from">
                <form class="form-ajax js-validate" data-thank="Спасибо, мы вскоре свяжемся с вами!">
                    <input type="hidden" name="Форма" value="Не нашли ответ на свой вопрос">
                    <div class="row">
                        <div class="col-md-8 col-md-push-2">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="ib mb--20">
                                        <input class="input input--geom-big input--theme-light-dark required" type="text" name="Имя" placeholder="Ваше имя">
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <label class="ib mb--20">
                                        <input class="input input--geom-big input--theme-light-dark required" type="email" name="Почта" placeholder="Ваш email" aria-required="true">
                                    </label>
                                </div>
                            </div>
                            <label class="ib mb--20">
                                <textarea name="Вопрос" class="input input--geom-big input--theme-light-dark required" placeholder="Вопрос"></textarea>
                            </label>
                            <div class="t--center">
                                <button class="btn btn--geom-big btn--geom-w190 btn--theme-yellow" type="submit">Отправить</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>



        </div>
    </div>

</main>
