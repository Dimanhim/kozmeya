<?
namespace app\components;

use app\models\Actions;
use app\models\Brands;
use app\models\Cart;
use app\models\Categories;
use app\models\Currency;
use app\models\Filters;
use app\models\Items;
use app\models\News;
use app\models\SettingsForms;
use app\models\Slider;
use app\models\Sliders;
use app\models\Tags;
use Yii;
use yii\helpers\Url;
use app\models\Seo;
use app\models\Redirects;
use app\models\StaticPage;
use app\models\Settings;
use yii\helpers\Json;
use yii\filters\AccessControl;

class Controller extends \yii\web\Controller
{
    public $layout = '@app/views/layouts/main';

	/* Url Vars */
	public $slugs = [];
	public $nonGetUrl = [];
	public $parentAlias = "";
	public $lastAlias = "";
	public $getString = "";
	/* End Url Vars */

    /* StaticPage Vars */
    public $pages = [];
    public $allPages = [];
    public $allPagesPid = [];
    public $topPages = [];
    public $topPagesPid = [];
    public $bottomPages = [];
    public $bottomPagesPid = [];
    public $leftPages = [];
    public $leftPagesPid = [];
    public $mainPages = [];
    public $mainPagesPid = [];
    /* End StaticPage Vars */

    /* Catalog Vars */
    public $catalogPageId = 2;
    public $allCategoriesPid = [];
    public $allCategories = [];
    public $categories = [];
    /* Catalog End Vars */

    /* Settings Vars */
    public $settings = [];
    /* End Settings Vars */

	/* Cart Vars */
	public $cartQty = 0;
	public $cartResultPrice = 0;
	public $cart = [];
	/* End Carts Vars */

    /* Favorites Vars */
    public $favorites = [];
    /* End Favorites Vars */

    /* Compares Vars */
    public $compares= [];
    /* End Compares Vars */
	
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            return true;
        } else {
            return false;
        }
    }

    public function init(){
        if(!isset(\Yii::$app->params['hasInit'])) {
            $this->initSession();
            $this->initVars();
            $this->initParams();
            $this->initSeo();
            $this->initRedirects();
            $this->initTranslates();

            $this->initSettings();
            $this->initGalleries();
            $this->initCurrencies();
            
            $this->initStaticPage();
            $this->initCatalog();

            $this->initFavorites();
            $this->initCart();
            $this->initCompares();
        }
        
        if(!isset($_COOKIE['sesCart'])) setcookie("sesCart", Yii::$app->session->getId(), time()+999999, '/');

        \Yii::$app->params['hasInit'] = true;

        parent::init();
    }
	
	public function initSession(){
		\Yii::$app->session->open();
	}

    public function initSettings()
    {
        $rows = Settings::find()->all();

        foreach($rows as $k=>$v){
            $this->settings[$v->id] = Yii::$app->langs->modelt($v, "text");
        }

        \Yii::$app->params['settings'] = $this->settings;

        $rows = SettingsForms::find()->all();
        foreach($rows as $k=>$v){
            \Yii::$app->params['settingsForms'][$v->code] = Json::decode($v->form_data);
        }


        if(isset(\Yii::$app->params['settingsForms']["mail"]["smtp"]) && \Yii::$app->params['settingsForms']["mail"]["smtp"] == 1){
            $smtpConfig = \Yii::$app->params['settingsForms']["mail"];
			Yii::$app->params['supportEmail'] = $smtpConfig['username'];
            unset($smtpConfig["smtp"]);
            $smtpConfig["class"] = 'Swift_SmtpTransport';
            Yii::$app->mailer->setTransport($smtpConfig);
        }
    }

    public function initTranslates(){
        \Yii::$app->langs->setup();
    }

    public function initGalleries()
    {
        $galleries = [];
        $rows = Sliders::find()->all();
        foreach($rows as $k=>$v){
            $galleries[$v->code] = $v;
        }

        \Yii::$app->params['galleries'] = $galleries;
    }

    public function initCatalog(){
        \Yii::$app->params['catalogPageId'] = $this->catalogPageId;
        $this->categories = Categories::find()->orderBy("posled")->all();
        \Yii::$app->params['categories']  = $this->categories;
        \Yii::$app->params['currentCategory']  = [];

        foreach($this->categories as $k=>$v){
            if($v->vis == 1) {
                $this->allCategories[$v->id] = $v;
                $this->allCategoriesPid[$v->parent][] = $v;

                \Yii::$app->params['allCategories']  = $this->allCategories;
                \Yii::$app->params['allCategoriesPid']  = $this->allCategoriesPid;

                if($v->menu == 1) {
                    \Yii::$app->params['menuCats'][]  = $v;
                    \Yii::$app->params['menuCatsPid'][$v->parent][]  = $v;
                }

                if($v->main == 1) {
                    \Yii::$app->params['mainCats'][]  = $v;
                    \Yii::$app->params['mainCatsPid'][$v->parent][]  = $v;
                }
            }
        }
    }


    public function initStaticPage(){
        $this->pages = StaticPage::find()->orderBy("posled")->all();
        \Yii::$app->params['pages']  = $this->pages;
        \Yii::$app->params['currentPage']  = [];

        foreach($this->pages as $k=>$v){
            if($v->vis == 1) {
                $this->allPages[$v->id] = $v;
                $this->allPagesPid[$v->parent][$v->id] = $v;

                \Yii::$app->params['allPages']  = $this->allPages;
                \Yii::$app->params['allPagesPid']  = $this->allPagesPid;

                if($v->top == 1) {
                    $this->topPages[$v->id] = $v;
                    $this->topPagesPid[$v->parent][$v->id] = $v;

                    \Yii::$app->params['topPages']  = $this->topPages;
                    \Yii::$app->params['topPagesPid']  = $this->topPagesPid;
                }
                if($v->bottom == 1) {
                    $this->bottomPages[$v->id] = $v;
                    $this->bottomPagesPid[$v->parent][$v->id] = $v;

                    \Yii::$app->params['bottomPages']  = $this->bottomPages;
                    \Yii::$app->params['bottomPagesPid']  = $this->bottomPagesPid;
                }
                if($v->left == 1) {
                    $this->leftPages[$v->id] = $v;
                    $this->leftPagesPid[$v->parent][$v->id] = $v;

                    \Yii::$app->params['leftPages']  = $this->leftPages;
                    \Yii::$app->params['leftPagesPid']  = $this->leftPagesPid;
                }
                if($v->main == 1) {
                    $this->mainPages[$v->id] = $v;
                    $this->mainPagesPid[$v->parent][$v->id] = $v;

                    \Yii::$app->params['mainPages']  = $this->mainPages;
                    \Yii::$app->params['mainPagesPid']  = $this->mainPagesPid;
                }
            }
        }
    }

	public function initCart(){
        $cookies = Yii::$app->response->cookies;

        /*if (!isset($_COOKIE['cartSID'])) {
            $cookies->add(new \yii\web\Cookie([
                    'name' => 'cartSID',
                    'value' => uniqid(),
            ]));
        }*/
		
        //$_SESSION["cartSID"] = (isset($_COOKIE['cartSID']) ? strtok($_COOKIE['cartSID'], ":") : Yii::$app->session->getId());
		
		\Yii::$app->params['cartSID'] = Yii::$app->session->getId();
		if(isset($_COOKIE['sesCart'])) \Yii::$app->params['cartSID'] = $_COOKIE['sesCart'];				
//        \Yii::$app->params['cartSID'] = \Yii::$app->session->getId();

		$this->cartQty = 0;
		$this->cartResultPrice = 0;
		$this->cart = [];
		
		if(\Yii::$app->params['cartSID'])
		{
			if($cart = Cart::findAll(['sid' => \Yii::$app->params['cartSID']])){
				foreach($cart as $k=>$v){
                    $price = \Yii::$app->catalog->itemCartPrice($v);

                    \Yii::$app->params['cart']['cartPrice'][$v->id] = $price;

					$this->cart[$v->id] = $v;
					$this->cartQty += $v->qty;
					$this->cartResultPrice += $price*$v->qty;
				}
				
				$this->cartResultPrice = round($this->cartResultPrice, 2);
			}
		}
		

		\Yii::$app->params['cart']['items']  = $this->cart;	
		\Yii::$app->params['cart']['qty']  = $this->cartQty;
		\Yii::$app->params['cart']['items_price']  = $this->cartResultPrice;
        \Yii::$app->params['cart']['delivery_price']  = 0;
        \Yii::$app->params['cart']['discount_value']  = (\Yii::$app->session->has("discount_value") ? \Yii::$app->session["discount_value"] : 0);
        \Yii::$app->params['cart']['discount_type']  = (\Yii::$app->session->has("discount_type") ? \Yii::$app->session["discount_type"] : 1);
        \Yii::$app->params['cart']['promocode']  = (\Yii::$app->session->has("promocode") ? \Yii::$app->session["promocode"] : "");

        if(\Yii::$app->session["discount_value"] > 0) {
            $this->cartResultPrice = (\Yii::$app->params['cart']['discount_type'] == 1 ? $this->cartResultPrice*((100-\Yii::$app->session["discount_value"]) / 100) : $this->cartResultPrice-\Yii::$app->session["discount_value"]);
        }

        \Yii::$app->params['cart']['price']  = $this->cartResultPrice;
	}

    public function initFavorites(){
        $this->favorites = [];

        if(\Yii::$app->session->has("favorites"))
        {
            $this->favorites = Items::find()->where(["IN", "id", array_keys(\Yii::$app->session["favorites"])])->all();
        }


        \Yii::$app->params['favorites']  = $this->favorites;
    }

    public function initCompares(){
        $this->compares = [];

        if(\Yii::$app->session->has("compares"))
        {
            $this->compares = Items::find()->where(["IN", "id", array_keys(\Yii::$app->session["compares"])])->all();
        }


        \Yii::$app->params['compares']  = $this->compares;
    }

    public function siteMap(){
        $sitemap = [];

        $sections = StaticPage::find()->where("vis = 1")->orderBy("posled")->all();
        foreach ($sections as $k => $v) {
            $sitemap[] = array('link' => $v->url, 'name' => $v->name);
        }

        $sections = News::find()->where("vis = 1")->orderBy("posled")->all();
        foreach ($sections as $k => $v) {
            $sitemap[] = array('link' => $v->url, 'name' => $v->name);
        }

        $sections = Brands::find()->where("vis = 1")->orderBy("posled")->all();
        foreach ($sections as $k => $v) {
            $sitemap[] = array('link' => $v->url, 'name' => $v->name);
        }

        $sections = Actions::find()->where("vis = 1")->orderBy("posled")->all();
        foreach ($sections as $k => $v) {
            $sitemap[] = array('link' => $v->url, 'name' => $v->name);
        }

        $sections = Categories::find()->where("vis = 1")->orderBy("posled")->all();
        foreach ($sections as $k => $v) {
            $sitemap[] = array('link' => $v->url, 'name' => $v->name);

            $subs = [];
            \Yii::$app->catalog->forceSubs($v->id, $subs);

            $tags = Tags::find()->where("vis = '1' AND category_id = '".$v->id."'")->orderBy("posled")->all();
            $brands = Brands::getBrands($subs);
            $countries = Brands::getCountries($brands);
            //$filters = Filters::find()->joinWith("categories")->where("filters.vis = '1' AND categories.id = '".$v->id."'")->orderBy("filters.posled")->all();

            foreach ($tags as $kk=>$vv) {
                $sitemap[] = array('link' => $vv->alias, 'name' => $vv->name);
            }

            foreach ($brands as $kk=>$vv) {
                $sitemap[] = array('link' => $v->url."/".$vv->alias, 'name' => $vv->name);
            }

            foreach ($countries as $kk=>$vv) {
                $sitemap[] = array('link' => $v->url."/".$vv->alias, 'name' => $vv->name);
            }
        }

        $sections = Items::find()->where("vis = 1")->orderBy("posled")->all();
        foreach ($sections as $k => $v) {
            $sitemap[] = array('link' => $v->url, 'name' => $v->name);
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
        <url>
            <loc>'.\Yii::$app->params['HOST'].'</loc>
            <lastmod>'.date("Y-m-d").'</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.9</priority>
        </url>';

        foreach ($sitemap as $k=>$v)
        {
            $xml .= "
			<url>
                <loc>".rtrim(\Yii::$app->params['HOST'],"/").$v['link']."</loc>
                <lastmod>".date('Y-m-d')."</lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.9</priority>
			</url>";
        }

        $xml .= '</urlset>';

        return ['xml' => $xml, 'elements' => $sitemap];
    }

    private function initCurrencies(){
        $currencies = Currency::find()->all();
        foreach($currencies as $k=>$v){
            \Yii::$app->params['currencies'][$v->id] = $v;
        }
    }

	private function initVars(){
        \Yii::$app->params['bcrumbs'] = [0 => ["link" => "/", "name" => "Главная"]];

		$this->nonGetUrl = trim(strtok(Yii::$app->request->url,'?'), '/');
        \Yii::$app->params['nonGetUrl'] = $this->nonGetUrl;

		$this->getString = (isset($_SERVER["REDIRECT_QUERY_STRING"]) && $_SERVER["REDIRECT_QUERY_STRING"] != "" ? $_SERVER["REDIRECT_QUERY_STRING"] : "");
        \Yii::$app->params['getString'] = $this->getString;

		$this->slugs = explode('/', $this->nonGetUrl);
        \Yii::$app->params['slugs'] = $this->slugs;

		$this->lastAlias = end($this->slugs);
        \Yii::$app->params['lastAlias'] = $this->lastAlias;

		$this->parentAlias = $this->slugs[0];
        \Yii::$app->params['parentAlias'] = $this->parentAlias;
    }
	
    private function initParams(){
        \Yii::$app->params['bodyClass'] = "";
        \Yii::$app->params['editLink'] = "";
		\Yii::$app->params['slugs'] = $this->slugs;
        \Yii::$app->params['controllerUrl'] = $this->parentAlias;
    }
	
	private function initSeo(){
        $get = Yii::$app->request->get();

        \Yii::$app->params['h1'] = "";
        \Yii::$app->params['seo_text'] = "";

        if(isset($get["page"]) && $get["page"] == 1) {
            $uri = str_replace("page=1", "", Yii::$app->request->url);
            $uri = str_replace("?&", "?", $uri);
            $uri = preg_replace('/\?$/', "", $uri);

            return $this->redirect(str_replace("page=1", "", $uri), 301)->send();
        }

        if($seo = Seo::findOne(['url' => "/".$this->nonGetUrl])){
			$this->setSeo($seo);
		}
    }

    public function setSeo($seo, $appenderByPaginator = ""){
        $appenderByPaginator .= Yii::$app->meta->pageAppender();

        if(isset($seo->title) && $this->view->title == "") $this->view->title = $seo->title.$appenderByPaginator;
        if(isset($seo->description) && $this->getMetaValue("description") == "") $this->view->registerMetaTag(['name' => 'description', 'content' => $seo->description]);
        if(isset($seo->keywords) && $this->getMetaValue("keywords") == "") $this->view->registerMetaTag(['name' => 'keywords', 'content' => $seo->keywords]);

        if(isset($seo->meta_title) && $this->view->title == "") $this->view->title = $seo->meta_title;
        if(isset($seo->meta_description) && $this->getMetaValue("description") == "") $this->view->registerMetaTag(['name' => 'description', 'content' => $seo->meta_description]);
        if(isset($seo->meta_keywords) && $this->getMetaValue("keywords") == "") $this->view->registerMetaTag(['name' => 'keywords', 'content' => $seo->meta_keywords]);

        if(isset($seo->h1) && $seo->h1 != "" && \Yii::$app->params['h1'] == "") \Yii::$app->params['h1'] = $seo->h1;
        if(isset($seo->text) && \Yii::$app->params['seo_text'] == "") \Yii::$app->params['seo_text'] = $seo->text;


    }



    private function getMetaValue($name){
        if(isset($this->view->metaTags)) {
            foreach($this->view->metaTags as $tag) {
                if(preg_match('/<meta name="'.$name.'" content="(.+?)">/', $tag, $value)) {
                    return $value[1];
                }
            }
        }

        return "";
    }
	
	private function initRedirects(){
        if($this->nonGetUrl == "index.php") {
            return $this->redirect("/", 301)->send();
        }

        if(Yii::$app->request->url != "/" && preg_match("/\/$/", strtok(Yii::$app->request->url,'?'))){
            return $this->redirect(rtrim(strtok(Yii::$app->request->url,'?'), "/").($this->getString != "" ? "?".$this->getString : ""), 301)->send();
        }

        if($redirect = Redirects::findOne(['url' => "/".$this->nonGetUrl])){
            return $this->redirect($redirect->redirect_url, $redirect->code)->send();
		}
    }
}