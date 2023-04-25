<?
namespace app\modules\admin\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use linslin\yii2\curl;

class Sms  extends Component
{
    public $url = "https://smsc.ru/sys/";
    public $postData = [];
    public $getData = [];
    public $config = [];

    public $actives = [];

    public function init(){
        parent::init();

        $this->postData = Yii::$app->request->post();
        $this->getData = Yii::$app->request->get();

        if(isset(\Yii::$app->params['settingsForms']["sms"])) {
            $this->config = \Yii::$app->params['settingsForms']["sms"]["config"];
            $this->actives = \Yii::$app->params['settingsForms']["sms"]["actives"];
        }
    }

    public function send($phones, $message){
        if(isset($this->config["login"], $this->config["password"])) {
            if($response = \Yii::$app->functions->curl($this->url."send.php", ['phones' => $phones, 'mes' => $message, 'login' => $this->config["login"], 'charset' => 'utf-8', 'psw' => $this->config["password"]])){
                if($response != "") {
                    return $response;
                }
            }
        }

        return false;

    }
}