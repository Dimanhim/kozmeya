<?php

namespace app\controllers;

use app\models\Brands;
use app\models\search\BrandsSearch;
use app\models\search\ItemsSearch;
use app\models\StaticPage;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class BrandsController extends \app\components\Controller
{
    public $page = [];
    public $pageId = 14;
    public $pageSize = 20;

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function init(){
        parent::init();

        $this->page = StaticPage::findOne($this->pageId);
        \Yii::$app->functions->add2navihierarchy($this->page);
        \Yii::$app->params['currentPage'] = $this->page;
    }

    public function actionIndex()
    {
        Yii::$app->functions->validateUrl(trim(\Yii::$app->functions->hierarchyUrl($this->page), "/"));

        $query = Yii::$app->request->queryParams;

        $allbrands = Brands::find()->where("vis = '1'")->orderBy("posled")->all();
        $alphabet = \Yii::$app->functions->getAlphabet($allbrands);

        $searchModel = new BrandsSearch();
        $dataProvider = $searchModel->search($query);

        $dataProvider->pagination->defaultPageSize = 10;

        \Yii::$app->params['editLink'] = "static/update?id=".$this->page->id;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'page' => $this->page,
            'alphabet' => $alphabet,
        ]);
    }

    public function actionShow()
    {
        $query = Yii::$app->request->queryParams;
        $alias = Yii::$app->request->queryParams['alias'];

        if($item = Brands::findOne(['alias' => $alias])){
            Yii::$app->functions->validateUrl(trim(\Yii::$app->functions->hierarchyUrl($this->page)."/".$item->alias, "/"));

            \Yii::$app->functions->add2navi($item->name);

            $query["filters"]["brands"][$item->id] = $item->id;

            $searchModel = new ItemsSearch();
            $dataProvider = $searchModel->search($query);

            if(isset($_GET["pageSize"])) {
                \Yii::$app->session["pageSize"] = $_GET["pageSize"];
            }

            $this->pageSize = (\Yii::$app->session->has("pageSize") ? \Yii::$app->session["pageSize"] : $this->pageSize);

            $dataProvider->pagination->defaultPageSize = $this->pageSize;

            $uri = $this->page->alias."/".$item->alias;

            $get = Yii::$app->request->get();
            unset($get["alias"]);
            $getParams = (count($get) > 0 ? "?".http_build_query($get) : "");

            $this->setSeo(\Yii::$app->meta->getMetaTempaltes($item));

            \Yii::$app->params['editLink'] = "brands/update?id=".$item->id;

            return $this->render('show', [
                'item' => $item,
                'page' => $this->page,
                'dataProvider' => $dataProvider,
                'pageSize' => $this->pageSize,
                'getParams' => $getParams,
                'uri' => $uri,
                'query' => $query,
            ]);
        }
        else{
            throw new \yii\web\NotFoundHttpException;
        }
    }
}
