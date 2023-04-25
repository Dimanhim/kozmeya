<?
namespace app\components;

use Yii;
use yii\base\Component;

class Kassa extends Component
{

    public $postData = [];
    public $getData = [];
    public $config = [];
    public $test = false;
    public $activeKassa = true;
    public $service = "";
    public $success = false;
    public $orderId;
    public $invoiceId;

    public function init(){
        parent::init();

        $this->postData = Yii::$app->request->post();
        $this->getData = Yii::$app->request->get();

        if(isset(\Yii::$app->params['settingsForms']["kassa"]["service"])) {
            if(\Yii::$app->params['settingsForms']["kassa"]["service"] == "yandex") {
                $this->service = \Yii::$app->params['settingsForms']["kassa"]["service"];
                $this->config = [
                    "shopId" => \Yii::$app->params['settingsForms']["kassa"]["config"]["shopId"],
                    "scid" => \Yii::$app->params['settingsForms']["kassa"]["config"]["scid"],
                    "shopPaymentUrl" => ($this->test ? "https://demomoney.yandex.ru/eshop.xml" : "https://money.yandex.ru/eshop.xml"),
                    "ShopPassword" => \Yii::$app->params['settingsForms']["kassa"]["config"]["password"],
                ];
            }

            if($this->service != "") {
                $this->activeKassa = true;
            }
        }
    }

    public function getConfig(){
        return $this->config;
    }

    public function checkOrder(){
        if($this->activeKassa && $this->service == "yandex") {
            $hash = md5($this->postData['action'].';'.$this->postData['orderSumAmount'].';'.$this->postData['orderSumCurrencyPaycash'].';'.$this->postData['orderSumBankPaycash'].';'.$this->config['shopId'].';'.$this->postData['invoiceId'].';'.$this->postData['customerNumber'].';'.$this->config['ShopPassword']);
            if (strtolower($hash) != strtolower($this->postData['md5'])){
                $code = 1;
                $this->success = true;
            }
            else {
                $code = 0;
                $this->success = false;
            }
            $result = '<?xml version="1.0" encoding="UTF-8"?>';
            $result .= '<checkOrderResponse performedDatetime="'. $this->postData['requestDatetime'] .'" code="'.$code.'"'. ' invoiceId="'. $this->postData['invoiceId'] .'" shopId="'. $this->config['shopId'] .'"/>';
            echo nl2br(trim(utf8_encode($result)));
            die();
        }

    }

    public function payment(){
        if($this->activeKassa && $this->service == "yandex") {
            $hash = md5($this->postData['action'] . ';' . $this->postData['orderSumAmount'] . ';' . $this->postData['orderSumCurrencyPaycash'] . ';' . $this->postData['orderSumBankPaycash'] . ';' . $this->config['shopId'] . ';' . $this->postData['invoiceId'] . ';' . $this->postData['customerNumber'] . ';' . $this->config['ShopPassword']);
            if (strtolower($hash) != strtolower($this->postData['md5'])) {
                $code = 1;
                $this->success = true;
                $this->orderId = $this->postData['orderNumber'];
                $this->invoiceId = $this->postData['invoiceId'];
            } else {
                $code = 0;
                $this->success = false;
            }

            $result = '<?xml version="1.0" encoding="UTF-8"?>';
            $result .= '<paymentAvisoResponse performedDatetime="' . $this->postData['requestDatetime'] . '" code="' . $code . '" invoiceId="' . $this->postData['invoiceId'] . '" shopId="' . $this->config['shopId'] . '"/>';

            echo nl2br(trim(utf8_encode($result)));
            die();
        }
    }

    public function generateLink($model){
        return \Yii::$app->params['HOST']."kassa?order_id=".$model->id."&hash=".md5($model->id.$model->date);
    }

    public function generatePaymentLink($model){
        $prices = \Yii::$app->catalog->orderPrice($model);
        $result_price = $model->delivery_price+$model->adding_price+$prices["result_price"];

        $params = [
            'shopId' => $this->config["shopId"],
            'scid' => $this->config["scid"],
            'sum' => $result_price,
            'customerNumber' => $model->user_id,
            'paymentType' => "AC",
            'orderNumber' => $model->id,
            'cps_phone' => '',
            'cps_email' => $model->email,
        ];

        return $this->config["shopPaymentUrl"]."?".http_build_query($params);
    }
}