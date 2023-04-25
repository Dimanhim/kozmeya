<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="no-print"><?= Html::encode($this->title) ?></h1>

<div class="box">
    <div class="items-index">

        <div class="box-header no-print">
            <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Экспорт', ['export', "delivery" => 1], ['class' => 'btn btn-default']) ?>
        </div>

        <div class="model-search box-header no-print">

            <div><a class="btn btn-primary btn-md showMe <? if(isset($_GET["filters"])):?>active<? endif;?>" data-selector=".showFilter" data-activetext="Скрыть фильтр" data-text="Показать фильтр"><? if(!isset($_GET["filters"])):?>Показать фильтр<? else:?>Скрыть фильтр<? endif;?></a></div>
            <hr>
            <div class="row showFilter" <? if(!isset($_GET["filters"])):?>style="display: none;"<? endif;?>>
            <form>
                <div class="form-group col-xs-12">
                    <label>Дата</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="daterangepicker-input form-control" name="filters[date]" value="<?=(isset($_GET["filters"]["date"]) ? $_GET["filters"]["date"] : date("Y-m-d")."/".date("Y-m-d"));?>">
                    </div>
                </div>

                <div class="form-group col-xs-12">
                    <input type="hidden" name="filters[delivery_id]" value="0">

                    <label>
                        <input type="checkbox" class="checkbox" name="filters[deliveries][2]" <?=(isset($_GET["filters"]["deliveries"][2]) ? "checked" : "");?> value="2"> Доставка
                    </label>
                    <label>
                        <input type="checkbox" class="checkbox" name="filters[deliveries][1]" <?=(isset($_GET["filters"]["deliveries"][1]) ? "checked" : "");?> value="1"> Самовывоз
                    </label>
                </div>


                <div class="form-group col-xs-12">
                    <?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
                    <?= Html::tag('a', 'Сбросить', ['href' => yii\helpers\Url::canonical(), 'class' => 'btn btn-default']) ?>
                </div>
            </form>

            <hr>
            </div>
        </div>

        <div class="box-body">
            <div class="deliveryMap no-print">
                <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

                <? foreach($dataProvider->getModels() as $k=>$v):?>
                    <? $prices = \Yii::$app->catalog->orderPrice($v);?>

                    <div class="mapPoints" data-address="<?=$v->address;?>"
                         data-header="Заказ №<?=$v->id;?>"
                         data-body="<?=$v->address;?>"
                         data-content="Доставка: <?=\Yii::$app->functions->getPrice($v->delivery_price);?> руб. Итого: <?=\Yii::$app->functions->getPrice($prices["result_price"]+$v->delivery_price+$v->adding_price);?> руб."
                         data-footer="Дата доставки: <?=date("d.m.Y", strtotime($v->delivery_date));?>
                                            <? if($v->delivery_time != "00:00:00"):?>, точное время доставки: <?=$v->delivery_time;?><? endif;?>
                                            <? if($v->delivery_time_range != ""):?>, время доставки: <?=$v->delivery_time_range;?><? endif;?>">
                    </div>
                <? endforeach;?>

                <div id="map" class="yandexMapPoints" style="width: 100%; height: 300px;" data-points=".mapPoints" data-center="[55.751574, 37.573856]"></div>
            </div>

            <form class="GridViewForm">
                <input type="hidden" name="model" value="app\models\Orders">

                <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th class="no-print"><input type="checkbox" class="select-on-check-all" name="selection_all" value="1"></th>
                        <th><legend>Данные покупателя</legend></th>
                        <th class="no-print"><legend>Состав заказа</legend></th>
                        <th><legend>Итого</legend></th>
                    </tr>
                    <? foreach($dataProvider->getModels() as $k=>$v):?>
                        <tr class="no-print goPrintRow">
                            <td class="no-print"><input type="checkbox" class="rowChecker" name="selection[]" value="1"></td>
                            <td>
                                <table class="table table-bordered">
                                    <tr>
                                        <td colspan="2" style="background: #23c55c;">
                                            <div>Дата доставки: <strong><?=date("d.m.Y", strtotime($v->delivery_date));?></strong></div>
                                            <? if($v->delivery_time != "00:00:00"):?><div>Точное время доставки: <?=$v->delivery_time;?></div><? endif;?>
                                            <? if($v->delivery_time_range != ""):?><div>Время доставки: <?=$v->delivery_time_range;?></div><? endif;?>
                                        </td>
                                    </tr>
                                    <tr><td>Ф.И.О</td><td><?=$v->name;?></td></tr>
                                    <tr><td>E-mail</td><td><?=$v->email;?></td></tr>
                                    <tr><td>Телефон</td><td><?=$v->phone;?></td></tr>
                                    <? if($v->delivery_id != 1):?><tr><td>Адрес</td><td><?=$v->address;?></td></tr><? endif;?>
                                    <? if($v->delivery_id == 1 && $v->pickupPoint):?>
                                        <tr>
                                            <td>Пункт самовывоза</td>
                                            <td><?=$v->pickupPoint->name;?></td>
                                        </tr>
                                    <? endif;?>
                                    <? if($v->comment != ""):?><tr><td>Комментарий</td><td><?=$v->comment;?></td></tr><? endif;?>
                                </table>
                            </td>
                            <td class="no-print">
                                <table class="table table-striped">
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Кол-во</th>
                                        <th>Название</th>
                                        <th>Артикул</th>
                                        <th>Цена</th>
                                        <th>Всего</th>
                                        <th>&nbsp;</th>
                                    </tr>

                                    <tbody>
                                    <? foreach($v->items as $kk=>$vv):?>
                                        <?=$this->render("/orders/_items", ['item' => $vv, 'edit' => false]);?>
                                    <? endforeach;?>
                                    </tbody>

                                </table>
                            </td>
                            <td>
                                <?=$this->render("/orders/_prices", ['model' => $v,]);?>
                            </td>
                        </tr>
                    <? endforeach;?>
                </table>
                </div>

                <div class="gridViewActions no-print" style="display: none;">
                    <a class="btn btn-success goPrintRowChecker">Печать</a>
                </div>
            </form>

            <div class="no-print">
                <?=LinkPager::widget([
                    'pagination'=>$dataProvider->pagination,
                ]);?>
            </div>
        </div>
    </div>
</div>