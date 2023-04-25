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
                        <span class="about-page-title__top">Надежность, проверенная<br> десятилетиями!</span><br>
                        <div class="about-page-title__main">
                            <ul>
                                <li>Один из лидеров  среди риэлторских компаний Москвы</li>
                                <li>Полный комплекс услуг</li>
                                <li>Удобное расположение офисов</li>
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

<div class="page-title mb--20"><?=Html::encode(\Yii::$app->meta->getPageTitle($page->name));?></div>


<div class="persons-grid persons-grid--5">
    <?php Pjax::begin([
        'id' => 'facesGrid',
        'enablePushState' => true,
    ]); ?>

    <? foreach($dataProvider->getModels() as $k=>$v):?>
    <div class="persons-grid__item">
        <div class="persons__item">
            <a class="persons__item-link" href="/<?=$page->alias;?>/<?=$v->id;?>">
                <span class="persons__item-img">
                    <img src="<?=\Yii::$app->functions->getUploadItem($v, "images", "fx", "125x125");?>" alt="<?=$v->name;?>" width="125" height="125">
                </span>
                <span class="persons__item-name"><?=$v->name;?></span>
                <span class="persons__item-post"><?=$v->post;?></span>
            </a>
            <div class="persons__item-socials">
                <? if($v->vk != ""):?>
                <a class="scoial-item" href="<?=$v->vk;?>" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M13.9 9.4v1.8c0 .4 0 .8.3 1 .2.1.7-.3.9-.6 1-1.2 1.4-2.7 2-4.1.3-.6.9-1 1.5-1 1.3 0 2.6 0 3.9.2.3 0 .5.4.4.7-.8 1.5-1.5 2.6-2.4 4-1.5 2.4-1.4 1.6.3 3.6.6.7 2.1 2.5 2.6 3.2.3.4-.4 1-.8 1h-4.4c-.3 0-.5-.1-2.4-2.3-.4-.4-.7-1-1.4-.9-.4.1-.4 1-.4 1.5 0 1.2.1 1.7-.2 1.7-1.8.1-2.7-.1-4.6 0-.9 0-6.2-7.4-8.1-10.7-.9-1.5-1-1.9.2-1.9.4 0 3 0 3.4-.1.5 0 .6.3 1.3 1.7.6 1.3 1.4 2.5 2.3 3.7.2.2.5.4.7.3.2-.1.2-.4.2-.6V8.4c0-.4.1-.8-.2-.9-.5-.1-1 0-1-.5s0-1 .5-1h4.8c.8 0 .6.5.6 1.3v2.1z"></path></svg>
                </a>
                <? endif;?>

                <? if($v->fb != ""):?>
                <a class="scoial-item" href="<?=$v->fb;?>" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M9.3,17.5c0-1.5,0-3.1,0-4.6c0-0.6-0.1-0.9-0.7-0.9c-0.4,0-1.1,0-1.4,0C7,12,7,11.8,7,11.7c0-0.9,0-2.3,0-3.2c0-0.3,0.3-0.3,0.8-0.3c0.2,0,0.6,0,0.9,0c0.5,0,0.6-0.3,0.6-0.8c0-0.8,0-1.6,0.1-2.4C9.4,3.9,9.8,2.8,10.7,2c0.6-0.5,1.3-0.8,2-0.9C13.6,1,14.6,1,15.5,1c0.5,0,0.9,0,1.4,0c0.2,0,0.3,0.2,0.3,0.3c0,1,0,2,0,3.1c0,0.2-0.2,0.3-0.3,0.4c-0.8,0-1.7,0-2.5,0.1c-0.3,0-0.5,0.2-0.5,0.5c-0.1,0.7-0.1,1.4,0,2.1c0,0.4,0.1,0.7,0.5,0.7c0.8,0,1.8,0,2.5,0c0.2,0,0.3,0.2,0.3,0.3c-0.1,0.9-0.2,1.8-0.3,2.7c-0.1,0.5,0,0.8-0.4,0.8c-0.5,0-1.6,0-2,0c-0.6,0-0.7,0.3-0.7,0.9c0,3.1,0,6.1,0,9.2c0,0.1,0,0.3,0,0.4c-0.1,0.3-0.4,0.5-0.7,0.5c-1,0-2,0-3.1,0c-0.5,0-0.7-0.3-0.7-0.8C9.3,20.6,9.3,19.1,9.3,17.5z"></path></svg>
                </a>
                <? endif;?>

                <? if($v->phone != ""):?>
                <a class="scoial-item" href="tel:+<?=\Yii::$app->functions->onlyNumbers($v->phone);?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M17,1L7,1C5.9,1,5,1.9,5,3v18c0,1.1,0.9,2,2,2h10c1.1,0,2-0.9,2-2V3C19,1.9,18.1,1,17,1z M17,19H7V5h10V19z"></path></svg>
                </a>
                <? endif;?>

                <? if($v->email != ""):?>
                <a class="scoial-item" href="mailto:<?=$v->email;?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M20,4H4C2.9,4,2,4.9,2,6l0,12c0,1.1,0.9,2,2,2h16c1.1,0,2-0.9,2-2V6C22,4.9,21.1,4,20,4zM20,8l-8,5L4,8V6l8,5l8-5V8z"></path></svg>
                </a>
                <? endif;?>
            </div>
            <a class="persons__item-go btn btn--geom-mid btn--theme-yellow" href="/<?=$page->alias;?>/<?=$v->id;?>">Подробнее</a>
        </div>
    </div>
    <? endforeach;?>

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

</div>

</div>

</main>