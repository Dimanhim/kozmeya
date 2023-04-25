<?
namespace app\components;

use app\models\Cart;
use app\models\Deliveries;
use app\models\Items;
use Yii;
use yii\base\Component;
use kartik\mpdf\Pdf;
use yii\db\Expression;

class Catalog  extends Component
{
    public function init(){
        parent::init();
    }

    public function categoryUrl($id){
        $url = $this->hierarchyUrl($id);
        //$url = "/".\Yii::$app->params['allCategories'][$id]->alias;

        return \Yii::$app->functions->hierarchyUrl(\Yii::$app->params['allPages'][\Yii::$app->params['catalogPageId']]).$url;
    }

    public function hierarchyUrl($id){
        $url = "";

        if(isset(\Yii::$app->params['allCategories'][$id])) {
            $this->hierarchy(\Yii::$app->params['allCategories'][$id], $hierarchies);
            $hierarchies = array_reverse($hierarchies);


            foreach ($hierarchies as $hierarchy) {
                $url .= "/".$hierarchy->alias;
            }
        }


        return $url;
    }

    public function itemUrl($model){
        return \Yii::$app->functions->hierarchyUrl(\Yii::$app->params['allPages'][\Yii::$app->params['catalogPageId']])."/".$model->alias;
    }

    public function forceSubs($id, &$ids)
    {
        if(isset(\Yii::$app->params['allCategoriesPid'][$id]))
        {
            foreach(\Yii::$app->params['allCategoriesPid'][$id] as $k=>$v)
            {
                if($v->vis)
                {
                    $ids[$v->id] = $v->id;
                }

                $this->forceSubs($v->id, $ids);
            }
        }

        $ids[$id] = $id;
    }

    public function forceParent($catData, &$parent)
    {
        if($catData->parent == 0) {
            $parent = $catData;
        }
        else {
            $this->forceParent(\Yii::$app->params['allCategories'][$catData->parent], $parent);
        }
    }

    public function hierarchy($data, &$datas)
    {
        $datas[$data->id] = \Yii::$app->params['allCategories'][$data->id];

        if(isset(\Yii::$app->params['allCategories'][$data->parent]))
        {
            $this->hierarchy(\Yii::$app->params['allCategories'][$data->parent], $datas);
        }
    }

    public function add2navihierarchy($data){
        $datas = [];

        $this->hierarchy($data, $datas);

        $datas = array_reverse($datas);

        foreach ($datas as $k=>$v) {
            Yii::$app->functions->add2navi($v->name, $this->categoryUrl($v->id));
        }
    }


    public function orderPrice($model)
    {
        $prices = ["price" => 0, "items_price" => 0, "result_price" => 0, "qty" => 0];

        if($model->items)
        {
            foreach($model->items as $k=>$v)
            {
                $prices["price"] += $v->price;
                $prices["items_price"] += $v->price*$v->qty;
                $prices["result_price"] += $v->price*$v->qty;
                $prices["qty"] += $v->qty;
            }
        }

        if($model->discount_value > 0) {
            $prices["result_price"] = ($model->discount_type == 1 ? $prices["result_price"]*((100-$model->discount_value) / 100) : $prices["result_price"]-$model->discount_value);
        }

        return $prices;
    }

    public function deliveryPrice($id){
        $price = 0;

        $delivery = Deliveries::find()->where(["id" => $id])->one();
        if($delivery) {
            $price = $delivery->price;
            if($delivery->prices) foreach($delivery->prices as $k=>$v){
                $condition = "return ".\Yii::$app->params['cart']['price'].$v->condition.$v->cart_price.";";
                if(eval($condition)) {
                    $price = $v->price;
                }
            }

            return $price;
        }
        else {
            return false;
        }

        return $price;
    }

    public function  itemPrice($model){
        $price = $model->price;
        if($model->vars) foreach($model->vars as $k=>$v){
            if($v->showtype->priceable && $v->values) {
                $price = $v->values[0]->price;
            }
        }

        return ['price' => $price];
    }

    public function  itemCartPrice($model){
        $price = $model->price;

        if($model->vars) foreach($model->vars as $k=>$v){
            if($v->var->showtype->priceable) {
                $price = $v->price;
            }
        }

        return $price;
    }

    public function currencyPrice($item, $field = "price", $wrap_price = "", $wrap_symbol = "", $another_price = ""){
        $lang = \Yii::$app->params['langs_code'][\Yii::$app->params['lang']];

        if($lang->currency) {
            $item->{$field} = $item->{$field}/$lang->currency->value;
            $item->currency_id = $lang->currency_id;
            if($another_price != "") {
                $another_price = $another_price/$lang->currency->value;
            }
        }

        return $this->viewPrice($item, $field, $wrap_price, $wrap_symbol, $another_price);
    }

    public function viewPrice($item, $field = "price", $wrap_price = "", $wrap_symbol = "", $another_price = ""){
        $before = "";
        $after = "";
        $price = \Yii::$app->functions->getPrice(($another_price != "" ? $another_price : $item->{$field}));

        if(isset(\Yii::$app->params['currencies'][$item->currency_id])) {
            $currency = \Yii::$app->params['currencies'][$item->currency_id];
            $symbol = $currency->symbol;

            if($currency->before) {
                $before = $symbol." ";
                if($wrap_symbol != "") {
                    $before = str_replace("$", $before, $wrap_symbol);
                }
            }
            else {
                $after = " ".$symbol;
                if($wrap_symbol != "") {
                    $after = str_replace("$", $after, $wrap_symbol);
                }
            }

            $price = \Yii::$app->functions->getPrice(($another_price != "" ? $another_price : $item->{$field}));
            if($wrap_price != "") {
                $price = str_replace("$", $price, $wrap_price);
            }
        }


        return $before.$price.$after;
    }

    public function getFilterPropValues($prop_id, $subs = [], $brand_id = ""){
        $join = \Yii::$app->params['filter']["join"];
        $sqlProps = \Yii::$app->params['filter']["sql"];

        $actives = \Yii::$app->db->createCommand("
            SELECT t.prop_id, t.value, COUNT(DISTINCT i.id) as count
            FROM items_props t
            LEFT JOIN items i ON i.id = t.item_id
            ".$join."
            WHERE i.vis = '1' AND i.parent = '0' AND t.prop_id = '" . $prop_id . "' ".$sqlProps."
            GROUP BY t.value
            ")->queryAll();

        foreach ($actives as $k=>$v){
            \Yii::$app->params['filter']['actives'][$prop_id][$v["value"]] = 1;
            \Yii::$app->params['filter']['counts'][$prop_id][$v["value"]] = $v["count"];
        }

        $values = \Yii::$app->db->createCommand("
            SELECT t.prop_id, t.value, COUNT(DISTINCT i.id) as count
            FROM items_props t
            LEFT JOIN items i ON i.id = t.item_id
            LEFT JOIN categoriestoitems ci ON ci.item_id = i.id
            WHERE i.vis = '1' AND i.parent = '0' AND t.prop_id = '" . $prop_id . "' AND ci.category_id IN (".implode(",", $subs).") ".($brand_id != "" ? " AND i.brand_id = '".$brand_id."'" : "")."
            GROUP BY t.value
            ")->queryAll();


        return $values;
    }

    public function sendOrderMail($model){
       /* $subject = (isset(\Yii::$app->params['settingsForms']["email_templates"]["new_order_subject"]) ? $model->regenTemplate(\Yii::$app->params['settingsForms']["email_templates"]["new_order_subject"]) : "Заказ №".$model->id." оформлен!");
        $msg = (isset(\Yii::$app->params['settingsForms']["email_templates"]["new_order"]) ? $model->regenTemplate(\Yii::$app->params['settingsForms']["email_templates"]["new_order"]) : "");


        Yii::$app->mailer->compose(['html' => 'order'],['model' => $model, 'text' => $msg])
            ->setTo(explode(",", Yii::$app->params["settings"][1]))
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['HOST']])
            ->setSubject($subject)
            ->send();

        Yii::$app->mailer->compose(['html' => 'order'],['model' => $model, 'text' => $msg])
            ->setTo(explode(",", $model->email))
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['HOST']])
            ->setSubject($subject)
            ->send();*/
    }

    public function pricePdf()
    {
        $data = Yii::$app->request->get();

        $subs = [];
        if(isset($data["category_id"]) && $data["category_id"] != "") {
            $this->forceSubs($data["category_id"], $subs);
        }

        $items = Items::find()->joinWith("categories")->where("items.vis = '1' AND items.price > 0".(count($subs) > 0 ? " AND categories.id IN (".implode(",", $subs).")" : ""))->orderBy("items.name DESC")->all();

        $content = \Yii::$app->controller->renderPartial("/catalog/pricepdf", ['items' => $items]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '@web/css/_system/catalog/pricepdf.css',
        ]);

        return $pdf->render();
    }

    public function hasRelationSection($data, $current){
        $has = false;

        if(!$current) {
            $has = true;
        }

        if(count($data) > 0 && $current) {
            foreach ($data as $k=>$v) {
                if($v->id == $current->id) $has = true;
            }
        }

        return $has;
    }

    public function deliveryAddDays(){
        $addDays = 2;

        if(date("N") == 5) {
            $addDays = 4;
        }
        elseif(date("N") == 6) {
            $addDays = 3;
        }
        else {
            if(date("H") < 15) {
                $addDays = 1;
            }
        }

        return $addDays;
    }

    public function telegramSend($msg){
        if(isset(\Yii::$app->params['settingsForms']["orders_settings"]["send_by_telegram"]) && \Yii::$app->params['settingsForms']["orders_settings"]["send_by_telegram"]) {
            if(isset(\Yii::$app->params['settingsForms']["orders_settings"]["telegram_bot_token"]) && \Yii::$app->params['settingsForms']["orders_settings"]["telegram_bot_token"] != "") {
                if(isset(\Yii::$app->params['settingsForms']["orders_settings"]["telegram_chat_id"]) && \Yii::$app->params['settingsForms']["orders_settings"]["telegram_chat_id"] != "") {
                    return Yii::$app->functions->curl("https://api.telegram.org/bot".\Yii::$app->params['settingsForms']["orders_settings"]["telegram_bot_token"]."/sendMessage", ["text" => $msg, "chat_id" => \Yii::$app->params['settingsForms']["orders_settings"]["telegram_chat_id"]]);
                }
            }

        }

        return false;
    }

    public function addToCart($item_id, $qty = 1, $vars = [], $color = "", $size = ""){
        $success = false;
        $error = "";

        $item = Items::findOne($item_id);
        if($item) {
            if(count($vars) > 0) {
                $cartWhere = "`cart`.`item_id` = ".Yii::$app->db->quoteValue($item->id)." AND `cart`.`sid` = '".\Yii::$app->params['cartSID']."'";

                foreach($vars as $var_id) {
                    $cartWhere .= " AND `cart`.`id` IN (SELECT `cart_id` FROM `cart_vars` WHERE `cart_vars`.`var_id` = ".Yii::$app->db->quoteValue($var_id).")";
                }

                $model = Cart::find()->joinWith("cartVars")->where($cartWhere)->having(['=', new Expression('COUNT(`cart_vars`.`var_id`)'), count($vars)])->one();
            }
            else {
                $model = Cart::find()->joinWith("vars")
                        ->where(['item_id' => $item->id, 'sid' => \Yii::$app->params['cartSID']])
                        ->andWhere(['IS', 'items_vars_values.id', (new Expression('NULL'))]);

                if($color != "") {
                    $model->andWhere(['color' => $color]);
                }

                if($size != "") {
                    $model->andWhere(['size' => $size]);
                }

                $model = $model->one();
            }

            if (!$model) $model = new Cart();

            $model->item_id = $item->id;
            $model->qty += $qty;
            $model->sid = \Yii::$app->params['cartSID'];
            $model->price = $item->price;
            $model->color = $color;
            $model->size = $size;

            if ($model->save()) {
                \Yii::$app->db->createCommand()->delete('cart_vars', ['cart_id' => $model->id,])->execute();

                if(count($vars) > 0) {
                    foreach($vars as $var_id) {
                        \Yii::$app->db->createCommand()->insert('cart_vars', ['cart_id' => $model->id, 'var_id' => $var_id])->execute();
                    }
                }

                $success = true;
            }
        }
        else {
            $error = "Товар не найден";
        }

        return ['success' => $success, 'error' => $error];
    }
}