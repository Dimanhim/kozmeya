<?
$percent = 0;
if($item->old_price > 0 && $item->old_price != $item->price)
{
    $percent = round((1 - $item->price / $item->old_price) * 100);
}
?>

<div id="fast_item" class="fst_v-form zoom-anim-dialog cardModalView">
    <div class="ta-c">
        <h3 class="h-3"><?=$item->name;?></h3>
    </div>
    <div class="row door-card-box">
        <div class="col-md-4">
            <div class="estimate-box">
                <div class="rev-name-r">
                    <div data-score="3" class="big-raity"></div>
                </div>
                <a href="#" class="estimate-el">Оценить</a>
            </div>
            <div class="card-detail-pic">
                <div class="card-detail-pic-d">
                    <img src="<?=\Yii::$app->functions->getUploadItem($item, "images", "fx", "245x550");?>" alt="<?=$item->name;?>">
                    <a href="#" class="fast__view addToFav" data-method="add" data-id="<?=$item->id;?>">Добавить в избранное</a>
                    <? if($item->new):?><div class="news__el">new</div><? endif;?>
                </div>

                <a href="#interier-<?=$item->id;?>" class="w-interier popup-with-move-anim openModalInterior">посмотреть в интерьере</a>                
            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="characteristics-area mt-47 bd-gr">
                        <h5>Характеристики</h5>
                        <table class="characteristics-table">
                            <tr>
                                <td>Производитель:</td>
                                <td> <a href="/<?=\Yii::$app->params['allPages'][14]->alias;?>/<?=$item->brand->alias;?>"><?=$item->brand->name;?></a>
                                </td>
                            </tr>

                            <? foreach($item->props as $k=>$v):?>
                            <tr>
                                <td><?=$v->prop->name;?>:</td>
                                <td><?=$v->value;?></td>
                            </tr>
                            <? endforeach;?>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="clearfix advantages-b">
                        <div class="fleft"><i class="icon-time"></i><span class="inbl">Доставка на следующий день</span>
                        </div>
                        <div class="fright"><i class="icon-show"></i><span class="inbl"><?=$item->status->name;?></span>
                        </div>
                    </div>
                    <div class="bd-gr itemRow">
                        <form class="addToCartForm">
                            <input type="hidden" name="method" value="add">
                            <input type="hidden" name="id" value="<?=$item->id;?>">
                            <input type="hidden" name="qty" value="1">

                            <?= $this->render('/catalog/parts/vars', ['item' => $item] ); ?>


                            <?= $this->render('/catalog/parts/prices', ['item' => $item] ); ?>

                            <div class="order-option-b order-option-b-last ta-c">
                                <button type="submit" class="send__dtn"><span class="red__btn red__btn-arrow">купить</span></button>
                            </div>
                        </form>

                        <a href="#" class="print__el"></a>
                    </div>
                </div>
                <div class="col-md-12"><a href="<?=\Yii::$app->catalog->itemUrl($item);?>" class="red__btn red__btn-arrow popup-more-btn">Подробнее</a>
                </div>
            </div>
        </div>
    </div>


    <div class="interier-form-wrap">
        <div class="interier-form-close"></div>
        <div id="interier-<?=$item->id;?>" class="interier-form zoom-anim-dialog">
            <div class="ajaxItemPhotos">
                <?= $this->render('/catalog/parts/photos', ['item' => $item] ); ?>
            </div>

            <? if((isset($also) && $also)):?>
                <div class="over-doors">
                    <h6>Другие варианты дверей</h6>
                    <div class="clearfix pag-popup">
                        <div class="pagination fright">

                        </div>
                    </div>
                    <ul class="list over-doors-list">
                        <? foreach($also as $k=>$v):?>
                            <li class="list over-doors-list-elem">
                                <img src="<?=\Yii::$app->functions->getUploadItem($v, "images", "fx", "66x141");?>" alt="<?=$v->name;?>">
                                <a href="#" class="getItemPhotos" data-id="<?=$v->id;?>"></a>
                            </li>
                        <? endforeach;?>
                    </ul>
                </div>
            <? endif;?>
        </div>
    </div><!--.interier-form-wrap-->

    <div class="gauger-form-wrap">
        <div class="gauger-form-wrap-close"></div>
        <form id="gauger" class="form-ajax popup-form zoom-anim-dialog gauger-form">
            <input type="hidden" name="Форма" value="Вызвать замерщика">
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

            <header class="popup-form-header">
                <div class="tag-elem">Бесплатная услуга</div>
                <h3 class="h-3">Вызвать замерщика</h3>
                <p>Наш специалист приедет в удобное для Вас время, произведёт замер и поможет подобрать подходящий Вам вариант</p>
            </header>
            <div class="form-box">
                <input type="text" placeholder="Имя" name="Имя" class="required">
            </div>
            <div class="form-box">
                <input type="email" placeholder="E-mail" name="E-mail" class="required">
            </div>
            <div class="form-box"><span class="float-text">Телефон</span>
                <input type="text" placeholder="+7 (" class="phoneJs required" name="Телефон">
            </div>
            <div class="form-box">
                <textarea placeholder="Сообщение" name="Сообщение" class="required"></textarea>
            </div>
            <div class="form-box ta-c">
                <button type="submit" class="send__dtn"><span class="red__btn red__btn-arrow">отправить        </span>
                </button>
            </div>
        </form>
    </div><!--.compos-form-wrap-->

    <div class="faq_meter-wrap">
        <div class="faq_meter-wrap-close"></div>
        <div id="faq_meter" class="popup-form zoom-anim-dialog faq_meter">
            <div class="ta-c">
                <h3 class="h-3">

                    Как сделать замеры
                </h3>
                <p>Вам нужно измерить: высоту и ширину дверного проема.
                    <br>По этим данным менеджер сможет рассчитать заказ.
                    <br>Размер дверного блока легко определить самостоятельно по таблице.</p>
            </div>
            <div class="clearfix meter-contain-area">
                <div class="meter-contain">
                    <div class="meter-contain-title">Ширина и высота блока<span>мм</span>
                    </div>
                    <ul class="list meter-contain-list">
                        <li>970х2060</li>
                        <li>970х2060</li>
                        <li>970х2060</li>
                    </ul>
                </div>
                <div class="meter-contain">
                    <div class="meter-contain-title">Ширина проёма<span>мм</span>
                    </div>
                    <ul class="list meter-contain-list">
                        <li>от 770 до 900</li>
                        <li>от 770 до 900</li>
                        <li>от 770 до 900</li>
                    </ul>
                </div>
                <div class="meter-contain">
                    <div class="meter-contain-title">Высота проёма<span>мм</span>
                    </div>
                    <ul class="list meter-contain-list">
                        <li>от 2060 до 2100</li>
                        <li>от 2060 до 2100</li>
                        <li>от 2060 до 2100</li>
                    </ul>
                </div>
                <div class="meter-contain">
                    <div class="meter-contain-title">Название модели<span>мм</span>
                    </div>
                    <ul class="list meter-contain-list">
                        <li>Входная дверь Соната</li>
                        <li>Входная дверь Соната</li>
                        <li>Входная дверь Соната</li>
                    </ul>
                </div>
            </div>
            <div class="meter-contain-warning">
                <div class="meter-contain-warning-title">Внимание</div>
                <div class="meter-contain-warning-text-out">
                    <div class="meter-contain-warning-text">
                        <p>Ширину и высоту проема нужно измерять от стены до стены. Если у вас сейчас установлена дверь, то нужно снять наличники, чтобы был доступен край стены.</p>
                    </div>
                    <div class="meter-contain-warning-text">
                        <p>Если ваш проем не попадает в указанный в таблице диапазон проемов, то его желательно сузить или расширить. Большой зазор между полотном и проемом отрицательно скажется на прочности крепления двери.</p>
                    </div>
                    <div class="meter-contain-warning-text">
                        <p>Если у вас нестандартная ситуация с проемом: стена в месте крепления двери обсыпается, проем сужен непрочной штукатуркой и др., то лучше вызвать мастера, который профессионально оценит ситуацию и даст рекомендации.</p>
                    </div>
                </div>
                <div class="ta-c"><a href="#gauger" class="red__btn red__btn-arrow popup-with-move-anim">Вызвать замерщика</a>
                </div>
            </div>
        </div>
    </div>
    

</div><!--#fast_item-->