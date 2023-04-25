<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\SettingsForms;
use Yii;
use app\models\Categories;
use app\models\Items;
use yii\console\Controller;
use yii\helpers\Json;


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class YmlController extends \app\components\Console
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        $settings = SettingsForms::findOne(["code" => "yandex_market"]);

        $yml="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
        $yml.="<!DOCTYPE yml_catalog SYSTEM \"".$this->params."utils/shops.dtd\">\n";
        $yml.="<yml_catalog date=\"".date("Y-m-d H:i")."\">\n";
        $yml.="<shop>\n";
        $yml.="<name>".$this->params."</name>\n";
        $yml.="<company>".$this->params."</company>\n";
        $yml.="<url>".$this->params."</url>\n";

        $yml.="<currencies>\n";
        $yml.="<currency  id=\"RUR\" rate=\"1\"/>\n";
        $yml.="</currencies>\n";

        // Categories
        $yml.="<categories>\n";
        $categories = Categories::find()->where("categories.vis = '1'")->all();
        foreach ($categories as $k=>$v)
        {
            $yml .= "<category id=\"".$v->id."\">".htmlspecialchars($v->name)."</category>\n";
        }
        $yml.="</categories>\n";

        // Offers
        $yml.='<offers>';

        $where = "items.vis = '1' AND categories.id IS NOT NULL";

        if($settings) {
            $settingsData = Json::decode($settings->form_data);

            if(isset($settingsData["categories"]) && count($settingsData["categories"]) > 0) $where .= " AND categories.id IN (".implode(",", $settingsData["categories"]).") ";
            if(isset($settingsData["brands"]) && count($settingsData["brands"]) > 0) $where .= " AND items.brand_id IN (".implode(",", $settingsData["brands"]).") ";
            if(isset($settingsData["items"]) && $settingsData["items"] != "") $where .= " AND items.id IN (".$settingsData["items"].") ";
            if((isset($settingsData["price_from"]) && $settingsData["price_from"] != "") || (isset($settingsData["price_to"]) && $settingsData["price_to"] != "")) {
                if($settingsData["price_from"] != "") $where .= " AND items.price >= '".$settingsData["price_from"]."' ";
                if($settingsData["price_to"] != "") $where .= " AND items.price <= '".$settingsData["price_to"]."' ";
            }
        }


        $items = Items::find()->joinWith("categories")->where($where)->all();

        foreach ($items as $k=>$v)
        {
            $yml.="<offer id=\"".($v->id)."\"  type=\"vendor.model\" available=\"".($v->status_id == 1 ? "true" : "false")."\">\n";
            $yml.="<price>".$v->price."</price>\n";
            if($v->old_price > $v->price) $yml.="<oldprice>".$v->old_price."</oldprice>\n";
            $yml .= "<url>".$this->params."/products/".urlencode(htmlspecialchars($v->alias))."</url>\n";
            $yml.="<currencyId>RUR</currencyId>\n";
            $yml.="<categoryId>".($v->categories[0]->id)."</categoryId>\n";
            if($v->images != '')
            {
                $yml.="<picture>".trim($this->params, "/")."/".trim(urlencode(htmlspecialchars(strip_tags(\Yii::$app->functions->getUploadItem($v, "images")))), "/")."</picture>\n";
            }


            $yml.="<delivery>true</delivery>\n";

            if(isset($v->delivery_price) && $v->delivery_price != ''){
                $delivery_cost = ($v->delivery_price == 'бесплатно') ? 0 : (int) $v->delivery_price;
                $yml.="<local_delivery_cost>".$delivery_cost."</local_delivery_cost>\n";
            }

            if(isset($v->warranty) && $v->warranty != ''){
                $warranty = ($v->warranty == 1) ? 'true' : 'false';
                $yml.="<manufacturer_warranty>".$warranty."</manufacturer_warranty>\n";
            }

            $yml.= "<vendor>".htmlspecialchars($v->brand->name)."</vendor>\n";
            $yml.= "<model>".htmlspecialchars($v->name)."</model>\n";

            $text  = "";
            if(!empty($v->text))
            {
                $text = htmlspecialchars(preg_replace("/\n\r/","",strip_tags(trim($v->text))));
            }

            $yml.="<description>".strip_tags($text)."</description>\n";
            $yml.="</offer>\n";
        }

        $yml.="</offers>\n";
        $yml.="</shop>\n";
        $yml.="</yml_catalog>\n";

        file_put_contents(__DIR__.'/../yml/yandex_market.xml', $yml);
        @chmod(__DIR__.'/../yml/yandex_market.xml', 0777);
    }
}
