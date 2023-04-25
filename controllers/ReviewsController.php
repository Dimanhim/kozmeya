<?php

namespace app\controllers;

use app\models\search\ReviewsSearch;
use app\models\StaticPage;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ReviewsController extends \app\components\Controller
{
    public $page = [];
    public $pageId = 3;

    public function init(){
        parent::init();

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
        Yii::$app->functions->validateUrl(trim(\Yii::$app->functions->hierarchyUrl($this->page), "/"));

        $query = Yii::$app->request->queryParams;

        $video = false;

        if(isset($query["hasvideo"])) {
            $video = true;
        }
        else {
            $query["hasvideo"] = 0;
        }

        $query["ReviewsSearch"]["vis"] = 1;

        $searchModel = new ReviewsSearch();
        $dataProvider = $searchModel->search($query);

        $dataProvider->pagination->defaultPageSize = 10;

        \Yii::$app->params['editLink'] = "static/update?id=".$this->page->id;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'page' => $this->page,
            'video' => $video,
        ]);
    }
}
