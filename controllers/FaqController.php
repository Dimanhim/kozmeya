<?php

namespace app\controllers;

use app\models\search\FaqSearch;
use app\models\StaticPage;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class FaqController extends \app\components\Controller
{
    public $page = [];
    public $pageId = 8;

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

        $searchModel = new FaqSearch();
        $dataProvider = $searchModel->search($query);

        $dataProvider->pagination->defaultPageSize = 10;

        \Yii::$app->params['editLink'] = "static/update?id=".$this->page->id;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'page' => $this->page,
        ]);
    }
}
