<?
namespace app\modules\admin\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use linslin\yii2\curl;

class Socials  extends Component
{
    public function init(){
        parent::init();
    }

    public function vkPost($content, $uri){
        $get = Yii::$app->request->get();

        if(isset($get["code"])) {
            $data = [
                    'code'          => $get["code"],
                    'client_id'     => "6235487",
                    'redirect_uri'  => $uri,
                    'client_secret' => "eWJNbSneyOOqJuHVEQdj",
                    'v'       => "5.21",
                    'scope'         => "wall,groups,offline",
            ];

            $response = Json::decode(Yii::$app->functions->curl("https://oauth.vk.com/access_token", $data, "get"), false);
            if(isset($response->access_token)) {
                $data = [
                        "owner_id" => 5132727,
                        "message" => $content,
                        "access_token" => $response->access_token,
                ];

                var_dump(Yii::$app->functions->curl("https://api.vk.com/method/wall.post", $data, "get"));exit();
            }
        }

        $data = [
                'response_type' => "code",
                'client_id'     => "6235487",
                'redirect_uri'  => $uri,
                'display'       => "page",
                'v'       => "5.21",
                'scope'         => "wall,groups,offline",
        ];

        return Yii::$app->getResponse()->redirect("https://oauth.vk.com/authorize?".http_build_query($data));
    }
}