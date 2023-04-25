<?
use yii\helpers\Html;
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

<div class="row">
    <div class="col-sm-5 col-md-6">
        <div class="person-img">
            <img src="<?=\Yii::$app->functions->getUploadItem($item, "bigimages");?>" alt="<?=$item->name;?>">
        </div>
    </div>
    <div class="col-sm-7 col-md-6">
        <div class="row mb--30">
            <div class="col-xs-8">
                <span class="title-2 mb--10"><?=$item->name;?></span>
                <span class="persons__post"><?=$item->post;?></span>
            </div>
            <div class="col-xs-4">
                <div class="persons__socials">
                    <? if($item->vk != ""):?>
                        <a class="scoial-item" href="<?=$item->vk;?>" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M13.9 9.4v1.8c0 .4 0 .8.3 1 .2.1.7-.3.9-.6 1-1.2 1.4-2.7 2-4.1.3-.6.9-1 1.5-1 1.3 0 2.6 0 3.9.2.3 0 .5.4.4.7-.8 1.5-1.5 2.6-2.4 4-1.5 2.4-1.4 1.6.3 3.6.6.7 2.1 2.5 2.6 3.2.3.4-.4 1-.8 1h-4.4c-.3 0-.5-.1-2.4-2.3-.4-.4-.7-1-1.4-.9-.4.1-.4 1-.4 1.5 0 1.2.1 1.7-.2 1.7-1.8.1-2.7-.1-4.6 0-.9 0-6.2-7.4-8.1-10.7-.9-1.5-1-1.9.2-1.9.4 0 3 0 3.4-.1.5 0 .6.3 1.3 1.7.6 1.3 1.4 2.5 2.3 3.7.2.2.5.4.7.3.2-.1.2-.4.2-.6V8.4c0-.4.1-.8-.2-.9-.5-.1-1 0-1-.5s0-1 .5-1h4.8c.8 0 .6.5.6 1.3v2.1z"></path></svg>
                        </a>
                    <? endif;?>

                    <? if($item->fb != ""):?>
                        <a class="scoial-item" href="<?=$item->fb;?>" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M9.3,17.5c0-1.5,0-3.1,0-4.6c0-0.6-0.1-0.9-0.7-0.9c-0.4,0-1.1,0-1.4,0C7,12,7,11.8,7,11.7c0-0.9,0-2.3,0-3.2c0-0.3,0.3-0.3,0.8-0.3c0.2,0,0.6,0,0.9,0c0.5,0,0.6-0.3,0.6-0.8c0-0.8,0-1.6,0.1-2.4C9.4,3.9,9.8,2.8,10.7,2c0.6-0.5,1.3-0.8,2-0.9C13.6,1,14.6,1,15.5,1c0.5,0,0.9,0,1.4,0c0.2,0,0.3,0.2,0.3,0.3c0,1,0,2,0,3.1c0,0.2-0.2,0.3-0.3,0.4c-0.8,0-1.7,0-2.5,0.1c-0.3,0-0.5,0.2-0.5,0.5c-0.1,0.7-0.1,1.4,0,2.1c0,0.4,0.1,0.7,0.5,0.7c0.8,0,1.8,0,2.5,0c0.2,0,0.3,0.2,0.3,0.3c-0.1,0.9-0.2,1.8-0.3,2.7c-0.1,0.5,0,0.8-0.4,0.8c-0.5,0-1.6,0-2,0c-0.6,0-0.7,0.3-0.7,0.9c0,3.1,0,6.1,0,9.2c0,0.1,0,0.3,0,0.4c-0.1,0.3-0.4,0.5-0.7,0.5c-1,0-2,0-3.1,0c-0.5,0-0.7-0.3-0.7-0.8C9.3,20.6,9.3,19.1,9.3,17.5z"></path></svg>
                        </a>
                    <? endif;?>

                    <? if($item->phone != ""):?>
                        <a class="scoial-item" href="tel:+<?=\Yii::$app->functions->onlyNumbers($item->phone);?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M17,1L7,1C5.9,1,5,1.9,5,3v18c0,1.1,0.9,2,2,2h10c1.1,0,2-0.9,2-2V3C19,1.9,18.1,1,17,1z M17,19H7V5h10V19z"></path></svg>
                        </a>
                    <? endif;?>

                    <? if($item->email != ""):?>
                        <a class="scoial-item" href="mailto:<?=$item->email;?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M20,4H4C2.9,4,2,4.9,2,6l0,12c0,1.1,0.9,2,2,2h16c1.1,0,2-0.9,2-2V6C22,4.9,21.1,4,20,4zM20,8l-8,5L4,8V6l8,5l8-5V8z"></path></svg>
                        </a>
                    <? endif;?>
                </div>
            </div>
        </div>

        <div class="person__descr text"><?=$item->text;?></div>

        <div class="person__buttons">
            <a class="btn btn--geom-big btn--theme-grey js-scroll-to-id" href="#preson-item-comments">Читать отзывы</a>
            <a class="btn btn--geom-big btn--theme-grey js-popup" href="#js-popup-ask">Задать вопрос</a>
            <a class="btn btn--geom-big btn--theme-yellow" href="#">Начать работу</a>
        </div>


    </div>
</div>
<hr class="hr--person-bottom">

<? if($item->items):?>
<div class="page-title mb--30">Объекты <span class="page-title__name"><?=$item->name;?></span></div>

<div class="cat-grid cat-grid--5 mb--20">
    <? foreach($item->items as $k=>$v):?>
        <?= $this->render('/catalog/parts/item', ['v' => $v] ); ?>
    <? endforeach;?>
</div>
<? endif;?>

<? if($item->reviews):?>
<div class="row mb--30">
    <div class="col-sm-7 col-md-8">
        <div class="page-title mb--10">Отзывы <span class="page-title__name">о работе <?=$item->name;?></span></div>
    </div>
    <div class="col-sm-5 col-md-4 t--right">
        <button class="btn btn--geom-big btn--theme-yellow js-add-person-comment-btn" type="button">Добавить свой отзыв</button>
    </div>
</div>

<div class="add-person-comment js-add-person-comment">
    <div class="add-comment-from">
        <form class="form-ajax js-validate" data-thank="Спасибо, за комментарий!">
            <input type="hidden" name="Форма" value="Отзыв о работе Александра Васина">
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
                <div class="col-xs-12">
                    <label class="ib mb--20">
                        <textarea name="Комментарий" class="input input--geom-big input--theme-light-dark required" placeholder="Комментарий"></textarea>
                    </label>
                </div>
            </div>
            <div class="fileupload__files_1"></div>
            <div class="t--right">
                <div class="file-upload  file-upload--doc">
                    <label>
                        <input class="popup-msg__input-file fileupload" data-files=".fileupload__files_1" type="file" name="files[]" multiple="" accept="image/*">
                        <span>Прикрепить файл</span><br>
                    </label>
                </div>


                <script src="/js/jQuery-File-Upload/js/vendor/jquery.ui.widget.js"></script>
                <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
                <script src="/js/jQuery-File-Upload/js/jquery.iframe-transport.js"></script>
                <!-- The basic File Upload plugin -->
                <script src="/js/jQuery-File-Upload/js/jquery.fileupload.js"></script>

                <button class="btn btn--geom-big btn--geom-w190 btn--theme-yellow" type="submit">Отправить</button>
            </div>
        </form>
    </div>
</div>

<div class="comments comments--person" id="preson-item-comments">
    <ul class="comments__list">
        <? foreach($item->reviews as $k=>$v):?>
            <?= $this->render('/reviews/parts/item', ['v' => $v, 'video' => false] ); ?>
        <? endforeach;?>
    </ul>


    <div class="t--center">
        <a class="btn btn--geom-big btn--theme-yellow" href="/<?=$page->alias;?>">Вернуться к списку сотрудников</a>
    </div>
</div>
<? endif;?>

</div>

</div>

</main>
