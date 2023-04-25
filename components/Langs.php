<?
namespace app\components;

use app\models\Translates;
use Yii;
use yii\base\Component;
use app\models\TranslatesGlobal;

class Langs  extends Component
{
    public function init(){
        parent::init();
    }

    public function setup(){
        \Yii::$app->params['lang'] = "ru";
        \Yii::$app->params['t'] = [];
        \Yii::$app->params['modelt'] = [];
        \Yii::$app->params['langs'] = [];

        if(\Yii::$app->session->has("lang") && \Yii::$app->session["lang"] != "") {
            \Yii::$app->params['lang'] = \Yii::$app->session["lang"];
        }

        $langs = \app\models\Langs::find()->where("vis = '1'")->all();
        if($langs) foreach ($langs as $k=>$v) {
            if($v->currency && $v->currency_last_update != date("Y-m-d")) {
                $response = Yii::$app->functions->curl("http://www.cbr.ru/scripts/XML_daily.asp", []);
                if($response) {
                    $response = new \SimpleXMLElement($response);
                    if(isset($response->Valute)) foreach ($response->Valute as $valute) {
                        if($valute->CharCode == $v->currency->code_cbr) {
                            $v->currency->value = floatval(str_replace(",", ".", $valute->Value));
                            $v->currency->save();
                            $v->currency_last_update = date("Y-m-d");
                            $v->save();
                        }
                    }
                }
            }

            \Yii::$app->params['langs'][$v->id] = $v;
            \Yii::$app->params['langs_code'][$v->code] = $v;
        }

        $global = TranslatesGlobal::find()->all();
        if($global) foreach ($global as $k=>$v) {
            \Yii::$app->params['t'][\Yii::$app->params['langs'][$v->lang_id]->code][$v->value] = $v->translate;
        }

        $modeltranslates = Translates::find()->all();
        if($modeltranslates) foreach ($modeltranslates as $k=>$v) {
            \Yii::$app->params['modelt'][\Yii::$app->params['langs'][$v->lang_id]->code][$v->model][$v->post_id][$v->field] = $v->value;
        }
    }

    public function t( $value )
    {
        return (isset(\Yii::$app->params['t'][\Yii::$app->params['lang']][$value]) ? \Yii::$app->params['t'][\Yii::$app->params['lang']][$value] : $value);
    }

    public function modelt($model, $field, $lang = "", $edit = false)
    {
        if($lang == "") $lang = \Yii::$app->params['lang'];

        return (isset(\Yii::$app->params['modelt'][$lang][\Yii::$app->functions->getModelName($model)][$model->id][$field]) ? \Yii::$app->params['modelt'][$lang][\Yii::$app->functions->getModelName($model)][$model->id][$field] : ($edit ? "" : $model->{$field}));
    }

    public function saveTranslates($model){
        $data = Yii::$app->request->post();
        if(isset($data["Translates"])) {
            foreach ($data["Translates"] as $lang_id => $values) {
                foreach ($values as $field => $value) {
                    \Yii::$app->db->createCommand("DELETE FROM `translates` WHERE `lang_id` = '".$lang_id."' AND `model` = '".\Yii::$app->functions->getModelName($model)."' AND `post_id` = '".$model->id."' AND `field` = '".$field."'")->execute();

                    if($value != "") {
                        \Yii::$app->db->createCommand("INSERT INTO `translates`(`lang_id`, `model`, `post_id`, `field`, `value`) VALUES (
                          '".$lang_id."', 
                          '".\Yii::$app->functions->getModelName($model)."', 
                          '".$model->id."',
                          '".$field."',
                          '".$value."'
                        )")->execute();
                    }

                }
            }
        }
    }
}