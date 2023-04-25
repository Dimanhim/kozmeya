<?php

namespace app\controllers;

use app\models\Orders;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Json;

class KassaController extends \app\components\Controller
{

    public $config = [];

    public function init(){
        parent::init();

        $this->config = \Yii::$app->kassa->getConfig();
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionCheck()
    {
        \Yii::$app->kassa->checkOrder();
    }

    public function actionPayment()
    {
        \Yii::$app->kassa->payment();
        if(\Yii::$app->kassa->success) {
            $order = Orders::findOne(\Yii::$app->kassa->orderId);
            if($order) {
                $order->status_id = \Yii::$app->params['settingsForms']["order_success_status"];
                $order->payment_invoice_id = \Yii::$app->kassa->invoiceId;
                $order->payment_date = date("Y-m-d H:i:s");
                $order->save();
            }
        }
    }

    public function actionIndex()
    {
        $form = false;
        $success = false;
        $error = false;

        if(!\Yii::$app->kassa->activeKassa) throw new \yii\web\NotFoundHttpException;

        \Yii::$app->functions->add2navi("Оплата заказа");

        if(isset($_GET["order_id"], $_GET["hash"]) && $_GET["order_id"] != "" && $_GET["hash"] != "")
        {
            $order = Orders::findOne($_GET["order_id"]);
            if($order && $_GET["hash"] == md5($order->id.$order->date))
            {
                $form = true;

                $prices = \Yii::$app->catalog->orderPrice($order);

                $result_price = $order->delivery_price+$order->adding_price+$prices["result_price"];
            }
            else {
                throw new \yii\web\NotFoundHttpException;
            }
        }
        else {
            throw new \yii\web\NotFoundHttpException;
        }


        return $this->render('index', [
            'config' => $this->config,
            'form' => $form,
            'success' => $success,
            'error' => $error,
            'order' => $order,
            'result_price' => $result_price,
        ]);
    }
}
