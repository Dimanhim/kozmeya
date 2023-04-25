<?php

namespace app\controllers;

use app\models\Actions;
use app\models\search\ActionsSearch;
use app\models\StaticPage;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ActionsController extends \app\components\Controller
{
    public $page = [];
    public $pageId = 15;

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

        $searchModel = new ActionsSearch();
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

        if($item = Actions::findOne(['alias' => $alias])){
            Yii::$app->functions->validateUrl(trim(\Yii::$app->functions->hierarchyUrl($this->page)."/".$item->alias, "/"));

            \Yii::$app->functions->add2navi($item->name);

            $this->setSeo(\Yii::$app->meta->getMetaTempaltes($item));

            \Yii::$app->functions->setViewer($item);

            $next = Actions::find()->where("vis = '1' AND id > '".$item->id."'")->one();
            $prev = Actions::find()->where("vis = '1' AND id < '".$item->id."'")->one();

            $also = Actions::find()->where(["!=", "id", $item->id])->limit(3)->all();

            \Yii::$app->params['editLink'] = "actions/update?id=".$item->id;

            return $this->render('show', [
                'item' => $item,
                'page' => $this->page,
                'next' => $next,
                'prev' => $prev,
                'also' => $also,
            ]);
        }
        else{
            throw new \yii\web\NotFoundHttpException;
        }
    }
}
