<?
namespace app\components;

use app\models\MetaTemplates;
use Yii;
use yii\base\Component;
use yii\helpers\Json;

class Meta  extends Component
{
    public function init(){
        parent::init();
    }

	public function getPageTitle($title = ""){
		return (isset(\Yii::$app->params['h1']) && \Yii::$app->params['h1'] != "" ? \Yii::$app->params['h1'] : $title).$this->pageAppender();
	}
	
	public function getSeoText(){
		return (isset(\Yii::$app->params['seo_text']) && \Yii::$app->params['seo_text'] != "" ? \Yii::$app->params['seo_text'] : "");
	}

    public function morpher($word){
        $return = [];

        if($response = \Yii::$app->functions->curl("http://ws3.morpher.ru/russian/declension", ['s' => $word])){
            if($response != "") {
                $response = (array) new \SimpleXMLElement($response);

                $return['ed'] = $response;
                if(isset($response['множественное'])) {
                    $return['mn'] = (array)$response['множественное'];
                }
            }
        }

        return $return;
    }

    public function getMorph($word, $edmn, $type, $lc = false){
        $return = [];

        if($lc) $word = mb_strtolower($word, "UTF-8");

        if($response = \Yii::$app->functions->curl("http://ws3.morpher.ru/russian/declension", ['s' => $word])){
            if($response != "") {
                $response = (array) new \SimpleXMLElement($response);

                $return['ed'] = $response;
                if(isset($response['множественное'])) {
                    $return['mn'] = (array)$response['множественное'];
                }
            }
        }

        return (isset($return[$edmn][$type])) ? $return[$edmn][$type] : $word;
    }

    public function getMetaTempaltes($model){
        $template = MetaTemplates::findOne(["active" => "1", "model" => \Yii::$app->functions->getModelName($model)]);
        if($template){
            $template->title = $this->updateMetaShortcodes($model, $template->title);
            $template->description = $this->updateMetaShortcodes($model, $template->description);
            $template->keywords = $this->updateMetaShortcodes($model, $template->keywords);

            return $template;
        }
    }

    public function updateMetaShortcodes($model, $template){
        preg_match_all('/\{(.*?)\}/', $template, $match);

        if(isset($match[1]) && is_array($match[1])) {
            foreach($match[1] as $field){
                //find params
                $fieldDb = $field;
                $s = false;
                $lc = false;
                $matchPad = array();

                if(preg_match_all('/--(\w+)/', $field, $matchParams)){
                    if(isset($matchParams[1]) && is_array($matchParams[1])) {

                        foreach ($matchParams[1] as $param) {
                            if($param == 's') {
                                $s = true;
                            }
                            if($param == 'lc') {
                                $lc = true;
                            }

                            $fieldDb = preg_replace('/--'.$param.'/u', "", $fieldDb);


                            preg_match('/:([а-яА-Я+])/u', $field, $matchPad);
                            if(isset($matchPad[1])) {
                                $fieldDb = preg_replace('/:'.$matchPad[1].'/u', "", $fieldDb);
                            }
                        }
                    }
                }

                //go change word
                if($fieldDb == 'h1') {
                    $returnField = $this->getPageTitle($model->name);
                }
                else {
                    $returnField = (isset($model->$fieldDb)) ? $model->$fieldDb : "";
                }


                if($returnField != "") {
                    if($lc) {
                        $returnField = mb_strtolower($returnField, "UTF-8");
                    }
                    if($s) {
                        $model_meta_data = Json::decode($model->meta_data);
                        if(!isset($model_meta_data[$field])){
                            $morper = $this->morpher($returnField);
                            if(isset($matchPad[1], $morper['ed'][$matchPad[1]])) $returnField = $morper['ed'][$matchPad[1]];

                            $meta_data[$field] = $returnField;
                            $model->meta_data = Json::encode($meta_data);
                            $model->save();
                        }
                        else {
                            $returnField = $model_meta_data[$field];
                        }

                    }



                    $template = preg_replace('/\{'.$field.'\}/', $returnField, $template);
                }
            }
        }

        return $template;
    }

    public function pageAppender(){
        $appenderByPaginator = "";

        $get = Yii::$app->request->get();
        if(isset($get["page"]) && $get["page"] > 1) {
            $appenderByPaginator .= " - страница ".$get["page"];
        }

        return $appenderByPaginator;
    }
}