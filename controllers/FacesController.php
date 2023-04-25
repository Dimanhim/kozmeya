<?php

namespace app\controllers;

use app\models\Faces;
use app\models\search\FacesSearch;
use app\models\StaticPage;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class FacesController extends \app\components\Controller
{
    public $page = [];
    public $pageId = 4;

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

        $searchModel = new FacesSearch();
        $dataProvider = $searchModel->search($query);

        $dataProvider->pagination->defaultPageSize = 10;

        \Yii::$app->params['editLink'] = "static/update?id=".$this->page->id;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'page' => $this->page,
        ]);
    }

    public function actionShow()
    {
        $alias = Yii::$app->request->queryParams['alias'];

        if($item = Faces::findOne(['id' => $alias])){
            Yii::$app->functions->validateUrl(trim(\Yii::$app->functions->hierarchyUrl($this->page)."/".$item->id, "/"));

            \Yii::$app->functions->add2navi($item->name);

            $this->setSeo(\Yii::$app->meta->getMetaTempaltes($item));

            \Yii::$app->params['editLink'] = "faces/update?id=".$item->id;

            return $this->render('show', [
                'item' => $item,
                'page' => $this->page,
            ]);
        }
        else{
            throw new \yii\web\NotFoundHttpException;
        }
    }
}
