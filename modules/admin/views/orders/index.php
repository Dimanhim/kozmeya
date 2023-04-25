<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="box">
    <div class="items-index">

        <div class="box-header">
            <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Экспорт', ['export'], ['class' => 'btn btn-default']) ?>
        </div>

        <?=$this->render('/components/system/_search', ['model' => $searchModel]); ?>

        <?php Pjax::begin([
            'id' => 'indexGrid',
            'enablePushState' => true,
        ]); ?>

        <? $statuses = \app\models\OrdersStatuses::find()->all();?>
        <? $deliveries = \app\models\Deliveries::find()->all();?>
        <? $payments = \app\models\Payments::find()->all();?>
        <? $pickupPoints = \app\models\Pickuppoints::find()->all();?>

        <div class="box-body">
            <?= Html::a("Обновить", ['orders/index'], ['class' => 'btn btn-primary', 'id' => 'refreshButton']) ?>
            <hr>

            <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th></th>
                    <th><legend>Информация о заказе</legend></th>
                    <th><legend>Данные покупателя</legend></th>
                    <th><legend>Состав заказа</legend></th>
                    <th>&nbsp;</th>
                </tr>
                <? foreach($dataProvider->getModels() as $k=>$v):?>
                    <tr>
                        <td style="background: <?=$v->status->color;?>;"></td>
                        <td>
                            <table class="table table-bordered">
                                <tr><td>ID</td><td><strong><?=$v->id;?></strong></td></tr>
                                <tr><td>Дата</td><td><strong><?=date("d.m.Y H:i:s", strtotime($v->date));?></strong></td></tr>

                                <tr>
                                    <td>Статус</td>
                                    <td>
                                        <select name="status_id" class="form-control fastChange" data-id="<?=$v->id;?>" data-model="\app\models\Orders">
                                            <? foreach($statuses as $status):?>
                                                <option <?=($v->status_id == $status->id ? "selected" : "");?> value="<?=$status->id;?>"><?=$status->name;?></option>
                                            <? endforeach;?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Тип доставки</td>
                                    <td>
                                        <select name="delivery_id" class="form-control fastChange" data-id="<?=$v->id;?>" data-model="\app\models\Orders">
                                            <? foreach($deliveries as $delivery):?>
                                                <option <?=($v->delivery_id == $delivery->id ? "selected" : "");?> value="<?=$delivery->id;?>"><?=$delivery->name;?></option>
                                            <? endforeach;?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Метод оплаты</td>
                                    <td>
                                        <select name="payment_id" class="form-control fastChange" data-id="<?=$v->id;?>" data-model="\app\models\Orders">
                                            <? foreach($payments as $payment):?>
                                                <option <?=($v->payment_id == $payment->id ? "selected" : "");?> value="<?=$payment->id;?>"><?=$payment->name;?></option>
                                            <? endforeach;?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <?=$this->render("/orders/_comments", ['model' => $v, 'add' => true, 'collapsed' => true]);?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class="table table-bordered">
                                <tr><td>Ф.И.О</td><td><?=$v->name;?></td></tr>
                                <tr><td>E-mail</td><td><?=$v->email;?></td></tr>
                                <tr><td>Телефон</td><td><?=$v->phone;?></td></tr>
                                <? if($v->delivery_id != 1):?><tr><td>Адрес</td><td><?=$v->address;?></td></tr><? endif;?>
                                <? if($v->delivery_id == 1 && $v->pickupPoint):?>
                                    <tr><td>Пункт самовывоза</td>
                                    <td>
                                        <select name="pickup_point_id" class="form-control fastChange" data-id="<?=$v->id;?>" data-model="\app\models\Orders">
                                            <? foreach($pickupPoints as $pickupPoint):?>
                                                <option <?=($v->pickup_point_id == $pickupPoint->id ? "selected" : "");?> value="<?=$pickupPoint->id;?>"><?=$pickupPoint->name;?></option>
                                            <? endforeach;?>
                                        </select>
                                    </td>
                                    </tr>
                                <? endif;?>
                                <? if($v->comment != ""):?><tr><td>Комментарий</td><td><?=$v->comment;?></td></tr><? endif;?>
                            </table>
                        </td>
                        <td>
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

                            <?=$this->render("/orders/_prices", ['model' => $v,]);?>
                            <?=$this->render("/orders/_actions", ['model' => $v,]);?>
                        </td>

                        <td>
                            <a href="/admin/orders/update?id=<?=$v->id;?>" title="Редактировать" aria-label="Редактировать" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a href="/admin/orders/delete?id=<?=$v->id;?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
                        </td>
                    </tr>
                <? endforeach;?>
            </table>
            </div>

            <?=LinkPager::widget([
                'pagination'=>$dataProvider->pagination,
            ]);?>
        </div>

        <?php Pjax::end(); ?>
    </div>
</div>