<?php

namespace app\controllers;

use app\models\Brands;
use app\models\Cart;
use app\models\Categories;
use app\models\Countries;
use app\models\Deliveries;
use app\models\Filters;
use app\models\FiltersValues;
use app\models\Items;
use app\models\Orders;
use app\models\OrdersItems;
use app\models\OrdersItemsVars;
use app\models\Partners;
use app\models\Payments;
use app\models\Pickuppoints;
use app\models\search\ItemsSearch;
use app\models\StaticPage;
use app\models\Tags;
use app\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Json;

class CatalogController extends \app\components\Controller
{
    public $page = [];
    public $pageId;
    public $pageSize = 20;

    public $tag;
    public $brand;
    public $country;
    public $filter;

    public $filtersData = [];

    public function init(){
        parent::init();

        $this->pageId = $this->catalogPageId;
        $this->page = StaticPage::findOne($this->pageId);
        \Yii::$app->functions->add2navihierarchy($this->page);
        \Yii::$app->params['currentPage'] = $this->page;
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        //Yii::$app->functions->validateUrl(trim(\Yii::$app->functions->hierarchyUrl($this->page), "/"));

        return $this->catalog();
    }

    public function actionShow()
    {
        $this->findFilter();
        $this->findBrand();
        $this->findCountry();
        $this->findTag();

        if($category = Categories::findOne(['alias' => $this->lastAlias])){
            $uri = trim(\Yii::$app->catalog->categoryUrl($category->id), "/");
            if($this->filter) {
                $uri .= "/".$this->filter->filter->alias."/".$this->filter->alias;
            }
            elseif($this->brand) {
                $uri .= "/".$this->brand->alias;
            }
            elseif($this->country) {
                $uri .= "/".$this->country->alias;
            }
            elseif($this->tag) {
                $uri = trim($this->tag->alias, "/");
            }

            //\Yii::$app->functions->validateUrl(trim($uri, "/"));

            \Yii::$app->params['currentCategory'] = $category;

            $subs = [];
            \Yii::$app->catalog->forceSubs($category->id, $subs);
            if($category->id != 37) {
                foreach ($subs as $sub) {
                    $this->filtersData["categories"][$sub] = $sub;
                }
            }
            else {
                $this->filtersData["categories"] = [];
            }

            
            \Yii::$app->catalog->add2navihierarchy($category);

            return $this->catalog($category);
        }
        elseif($item = Items::find()->where(['alias' => $this->lastAlias])->andWhere(["vis" => 1])->one()){
            return $this->item($item);
        }
        else{
            throw new \yii\web\NotFoundHttpException;
        }
    }

    public function item($item){
        //\Yii::$app->functions->validateUrl(trim(\Yii::$app->catalog->itemUrl($item), "/"));

        $category = $item->_categoryData;

        if($category) \Yii::$app->catalog->add2navihierarchy($category);
        \Yii::$app->functions->add2navi($item->name);

        $similar = ($category ? Items::find()->where("items.vis = '1' AND items.id != '".$item->id."' AND categories.id = '".$category->id."'")->joinWith("categories")->all() : false);

        $this->setSeo(\Yii::$app->meta->getMetaTempaltes($item));

        if(!isset(\Yii::$app->session["view_item"][$item->id])) {
            $also = \Yii::$app->session["view_item"];
            $also[$item->id] = $item->id;
            \Yii::$app->session["view_item"] = $also;
        }

        $also = Items::find()->where("vis = '1' AND id != '".$item->id."' AND id IN (".implode(",", array_keys(\Yii::$app->session["view_item"])).")")->all();

        $brands = [];

        if($category) {
            $subs = [];
            \Yii::$app->catalog->forceSubs($category->id, $subs);
            $brands = Brands::getBrands($subs);
        }

        \Yii::$app->params['editLink'] = "items/update?id=".$item->id;

        return $this->render('item', [
            'item' => $item,
            'similar' => $similar,
            'also' => $also,
            'page' => $this->page,
            'category' => $category,
            'brands' => $brands,
        ]);
    }

    public function catalog($category = null){
        $surl = $this->page->alias;
        $search = false;

        $query = Yii::$app->request->queryParams;

        if (Yii::$app->request->isAjax && isset($_POST["getCount"])) {
            $query = Yii::$app->request->post();
        }

        if($category->id == 37) {
            if(isset($query["filters"]["categories"])) $this->filtersData["categories"] = $this->filtersData["categories"] + $query["filters"]["categories"];
            unset($this->filtersData["categories"][37]);
            $this->filtersData["special"] = 1;
        }

        if(isset($query["s"])){
            $search = true;
            $this->filtersData["s"] = $query["s"];
            //\Yii::$app->session["s"][$query["s"]] = $query["s"];
        }

        if($this->brand) {
            $this->filtersData["brands"][$this->brand->id] = $this->brand->id;
            \Yii::$app->functions->add2navi($this->brand->name);
        }

        if($this->country) {
            $this->filtersData["countries"][$this->country->id] = $this->country->id;
            \Yii::$app->functions->add2navi($this->country->name);
        }

        if($this->tag) {
            $tagurl = parse_url($this->tag->url);
            parse_str(rawurldecode($tagurl['query']), $params);

            $query = $query + $params;

            \Yii::$app->functions->add2navi($this->tag->name);
        }

        if($this->filter) {
            $this->filtersData["props"][$this->filter->filter->id][$this->filter->id] = $this->filter->id;

            \Yii::$app->functions->add2navi($this->filter->name);
        }

        $this->filtersData = (isset($query["filters"]) ? $this->filtersData + $query["filters"] : $this->filtersData);
        if(isset($query['filters'])){
            $this->filtersData = $this->filtersData + $query['filters'];
        }

        $searchModel = new ItemsSearch();
        $searchModel->filtersData = $this->filtersData;

        $dataProvider = $searchModel->search($query);

        if(isset($_GET["pageSize"])) {
            \Yii::$app->session["pageSize"] = $_GET["pageSize"];
        }

        $this->pageSize = (\Yii::$app->session->has("pageSize") ? \Yii::$app->session["pageSize"] : $this->pageSize);

        $dataProvider->pagination->defaultPageSize = $this->pageSize;

        foreach($dataProvider->getModels() as $k=>$v) {
            //echo $v->name."<br>";
        }

        $categories = Categories::find()->where("vis = '1'")->orderBy("posled")->all();

        $uri = implode("/", $this->slugs);

        $get = Yii::$app->request->get();
        unset($get["alias"]);
        $getParams = (count($get) > 0 ? "?".http_build_query($get) : "");

        $tags = [];
        $brands = [];
        $filters = [];
        $countries = [];

        if($category) {
            $surl = trim(\Yii::$app->catalog->categoryUrl($category->id), "/");

            $subs = [];
            \Yii::$app->catalog->forceSubs($category->id, $subs);

            //Gets
            $tagsCustoms = Tags::find()->where("vis = '1' AND custom_url = '/".$this->nonGetUrl."'")->orderBy("posled")->all();
            $tagsCategories = Tags::find()->where("vis = '1' AND category_id = '".$category->id."'")->orderBy("posled")->all();
            $tags = $tagsCustoms + $tagsCategories;

            $brands = Brands::getBrands($subs);

            $countries = Brands::getCountries($brands);

            $filters = Filters::find()->joinWith("categories")->where("filters.vis = '1' AND categories.id = '".$category->id."'")->orderBy("filters.posled")->all();

            if(!Yii::$app->catalog->hasRelationSection($brands, $this->brand)) throw new \yii\web\NotFoundHttpException;
            if(!Yii::$app->catalog->hasRelationSection($countries, $this->country)) throw new \yii\web\NotFoundHttpException;

            if($this->filter) {
                $this->setSeo($this->filter);
            }

            $this->setSeo(\Yii::$app->meta->getMetaTempaltes($category));

            if($this->filter) {
                \Yii::$app->params['editLink'] = "filters/update?id=".$this->filter->filter->id;
            }
            elseif($this->brand) {
                \Yii::$app->params['editLink'] = "brands/update?id=".$this->brand->id;
            }
            elseif($this->country) {
                \Yii::$app->params['editLink'] = "countries/update?id=".$this->country->id;
            }
            elseif($this->tag) {
                \Yii::$app->params['editLink'] = "tags/update?id=".$this->tag->id;
            }
            else {
                \Yii::$app->params['editLink'] = "categories/update?id=".$category->id;
            }
        }
        else {
            \Yii::$app->params['editLink'] = "static/update?id=".$this->page->id;
        }

        if (Yii::$app->request->isAjax && isset($_POST["getCount"])) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return ['count' => $dataProvider->totalCount];
        }
        else {
            return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'categories' => $categories,
                    'query' => $query,
                    'category' => $category,
                    'uri' => $uri,
                    'surl' => $surl,
                    'getParams' => $getParams,
                    'page' => $this->page,
                    'tags' => $tags,
                    'tag' => $this->tag,
                    'brand' => $this->brand,
                    'country' => $this->country,
                    'filter' => $this->filter,
                    'brands' => $brands,
                    'countries' => $countries,
                    'filters' => $filters,
                    'pageSize' => $this->pageSize,
                    'search' => $search,
            ]);
        }
    }

    public function actionCart()
    {
        $amocrm = Yii::$app->amocrm->getClient();

        $model = new Orders();
        $success = false;
        $step = 1;

        $query = Yii::$app->request->queryParams;

        if(isset($query["step"]) && $query["step"] != "") {
            $step = $query["step"];
        }

        $postData = Yii::$app->request->post();

        if(isset($query["truncate"]) && $query["truncate"] == 1) {
            foreach(\Yii::$app->params['cart']['items'] as $k=>$v) {
                Cart::findOne(['id' => $v->id, 'sid' => \Yii::$app->params['cartSID']])->delete();
            }

            $this->redirect("/cart");
        }

        if(isset($query["delete"]) && $query["delete"] != "") {
            if(Cart::findOne(['id' => $query["delete"], 'sid' => \Yii::$app->params['cartSID']])->delete()){
                $this->redirect("/cart");
            }
        }

        $deliveries = Deliveries::find()->where(["vis" => 1])->orderBy("posled")->all();
        $payments = Payments::find()->where(["vis" => 1])->orderBy("posled")->all();
        $pickuppoints = Pickuppoints::find()->where(["vis" => 1])->orderBy("posled")->all();

        $deliveriesById = [];
        foreach($deliveries as $k=>$v){
            $deliveriesById[$v->id] = $v;
        }

        if(Yii::$app->request->isPost && isset($postData["Orders"])) 
        {
	        

            $model->status_id = 1;
            $model->deleted = 0;
            $model->user_id = 0;

            if(!\Yii::$app->siteuser->isGuest) {
                $model->user_id = Yii::$app->siteuser->getId();
            }

            if(isset($postData["Orders"]["delivery_id"]) && $postData["Orders"]["delivery_id"] != 1) {
                $model->pickup_point_id = 1;
                unset($postData["Orders"]["pickup_point_id"]);
            }

            $model->promocode 	   = \Yii::$app->params['cart']['promocode'];
            $model->discount_value = \Yii::$app->params['cart']['discount_value'];
            $model->discount_type  = \Yii::$app->params['cart']['discount_type'];
            $model->delivery_price = \Yii::$app->catalog->deliveryPrice($postData["Orders"]["delivery_id"]);
            $model->delivery_date  = date("Y-m-d", strtotime("+3 days"));

            if(isset($postData["Orders"]["adding_price"]) && $postData["Orders"]["adding_price"] > 0) {
                $model->adding_price_text = "Плюс установка";
                $model->adding_price = 0;
            }

            foreach($postData["Orders"] as $field=>$value){
                $model->{$field} = $value;
            }

            if(isset($postData["city_another"]) && $postData["city_another"] != "") {
                $model->city = trim($postData["city_another"]);
            }

            if(isset($postData["delivery_price"]) && $postData["delivery_price"] != "") {
                $model->delivery_price = \Yii::$app->catalog->deliveryPrice($postData["delivery_price"]);
            }
			if ($model->delivery_price == '') $model->delivery_price = 0;


            if($model->save())
            {
	            
                if(count(\Yii::$app->params['cart']['items']) > 0) 
                {
	                
                    foreach(\Yii::$app->params['cart']['items'] as $k=>$v)
                    {
	                    

                        $orderitems = new OrdersItems();
                        $orderitems->order_id = $model->id;
                        $orderitems->item_id = $v->item_id;
                        $orderitems->name = $v->item->name;
                        $orderitems->price = \Yii::$app->params['cart']['cartPrice'][$v->id];
                        $orderitems->qty = $v->qty;
                        $orderitems->item_params_json = Json::encode($v->item);
                        $orderitems->var_params_json = ($v->vars ? Json::encode($v->vars) : "");
                        $orderitems->color = $v->color;
                        $orderitems->size = $v->size;

                        if($orderitems->save()) {
                            if($v->vars) foreach($v->vars as $kk=>$vv) {
                                $orderitemsvars = new OrdersItemsVars();
                                $orderitemsvars->order_item_id = $orderitems->id;
                                $orderitemsvars->var_id = $vv->id;
                                $orderitemsvars->save();
                            }
                        }
                    }
                }
	
                $model->save();

                $model = Orders::findOne($model->id);
                $prices = \Yii::$app->catalog->orderPrice($model);

                \Yii::$app->catalog->sendOrderMail($model);
                \Yii::$app->catalog->telegramSend("Новый заказ #".$model->id);

                $success = true;

                if(\Yii::$app->siteuser->isGuest) {
                    $newUser = Users::register(["email" => $model->email, "username" => $model->email, "password" => uniqid()], false, false);
                    if($newUser["success"] && isset($newUser["model"]->id)) {
                        $model->user_id = $newUser["model"]->id;
                    }
                }

                /* AMOCRM*/
                /*$contact = $amocrm->contact;

                $contact['name'] = $model->name;
                $contact['request_id'] = $model->user_id;
                $contact['date_create'] = time();
                $contact['company_name'] = $model->company;
                $contactId = $contact->apiAdd();

                $lead = $amocrm->lead;

                $lead['name'] = 'Заказ №'.$model->id;
                $lead['date_create'] = time();
                $lead['price'] = $prices["result_price"];
                $lead['sale'] = $prices["result_price"];
                $lead['contacts_id'] = $contactId;
                $lead->apiAdd();*/
                /* END AMOCRM */

                Cart::deleteAll(['sid' => \Yii::$app->params['cartSID']]);

                unset(\Yii::$app->session["promocode"]);
                unset(\Yii::$app->session["discount_value"]);

                if(isset(\Yii::$app->params['settingsForms']["kassa"]["redirecttopayment"]) && \Yii::$app->params['settingsForms']["kassa"]["redirecttopayment"] == 1){
                    return $this->redirect(Yii::$app->kassa->generatePaymentLink($model));
                }
            }

        }

        return $this->render('cart', [
            'deliveries' => $deliveries,
            'deliveriesById' =>$deliveriesById,
            'payments' => $payments,
            'pickuppoints' => $pickuppoints,
            'success' => $success,
            'model' => $model,
            'step' => $step,
        ]);
    }

    public function actionFavorites()
    {
        return $this->render('favorites', [

        ]);
    }

    public function actionCompare()
    {
        $get = Yii::$app->request->get();

        $props = ["props" => [], "values" => []];

        if(count(\Yii::$app->params["compares"])) {
            if(isset($get["truncate"]) && $get["truncate"] == 1) {
                foreach(\Yii::$app->params["compares"] as $k=>$v) {
                    $compares = \Yii::$app->session["compares"];
                    unset($compares[$v->id]);
                    \Yii::$app->session["compares"] = $compares;
                }

                $this->initCompares();
            }

            foreach(\Yii::$app->params["compares"] as $k=>$v){
                if($v->props) foreach($v->props as $kk=>$vv){
                    $props["props"][$vv->id] = $vv;
                    $props["values"][$v->id][$vv->id] = $vv;
                }
            }

            if(isset($get["diff"]) && $get["diff"] == 1) {
                if(count($props["props"]) >0) foreach ($props["props"] as $prop_id => $prop) {
                    $valuesArray = [];

                    foreach (\Yii::$app->params["compares"] as $k => $v) {
                        $value = (isset($props["values"][$v->id][$prop_id]) ? $props["values"][$v->id][$prop_id]->value : '-');
                        $valuesArray[$value] = $prop_id;
                    }

                    if(count($valuesArray) == 1) {
                        foreach($props["values"] as $item_id =>$props) {
                            foreach($valuesArray as $k => $v){
                                unset($props["values"][$item_id][$v]);
                                unset($props["props"][$v]);
                            }
                        }
                    }
                }
            }
        }



        return $this->render('compare', [
            'props' => $props,
        ]);
    }

    public function findTag(){
        $this->tag = Tags::find()->where("alias = '".$this->nonGetUrl."' OR alias = '/".$this->nonGetUrl."'")->one();
        if($this->tag){
            $taguridata = explode("/", trim(strtok($this->tag->url, "?"), "/"));
            $this->lastAlias = end($taguridata);
        }
    }

    public function findBrand(){
        $this->brand = Brands::findOne(['alias' => end($this->slugs)]);
        if($this->brand){
            $this->lastAlias = $this->slugs[count($this->slugs)-2];
        }
    }

    public function findCountry(){
        $this->country = Countries::findOne(['alias' => end($this->slugs)]);
        if($this->country){
            $this->lastAlias = $this->slugs[count($this->slugs)-2];
        }
    }

    public function findFilter(){
        if(count($this->slugs) > 3) {
            $this->filter = FiltersValues::find()->joinWith("filter")->where(['filters_values.alias' => end($this->slugs), 'filters.alias' => $this->slugs[count($this->slugs)-2]])->one();
            if($this->filter){
                $this->lastAlias = $this->slugs[count($this->slugs)-3];
            }
        }

    }

    public function actionPricepdf()
    {
        $this->layout = false;

        return \Yii::$app->catalog->pricePdf();
    }
}
