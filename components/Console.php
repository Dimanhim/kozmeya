<?
namespace app\components;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\filters\AccessControl;

class Console extends \yii\console\Controller
{
    public $params = [
        "HOST" => "http://backoffice.vikitest.pro",
        "supportEmail" => "info@backoffice.vikitest.pro",
    ];

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            return true;
        } else {
            return false;
        }
    }

    public function init(){

        parent::init();
    }
}