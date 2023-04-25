<?php

namespace app\controllers;

use app\models\Vacancies;
use app\models\VacanciesCategories;
use app\models\search\VacanciesSearch;
use app\models\StaticPage;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class VacanciesController extends \app\components\Controller
{
    public $page = [];
    public $pageId = 11;

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

        $items = Vacancies::find()->where("vis = '1'")->orderBy("posled")->all();

        \Yii::$app->params['editLink'] = "static/update?id=".$this->page->id;

        return $this->render('index', [
            'items' => $items,
            'page' => $this->page,
        ]);
    }

    public function actionShow()
    {
        $alias = Yii::$app->request->queryParams['alias'];

        $items = Vacancies::find()->where("vis = '1'")->orderBy("posled")->all();

        if($item = Vacancies::findOne(['id' => $alias])){
            Yii::$app->functions->validateUrl(trim(\Yii::$app->functions->hierarchyUrl($this->page)."/".$item->id, "/"));

            \Yii::$app->functions->add2navi($item->name);

            $this->setSeo(\Yii::$app->meta->getMetaTempaltes($item));

            \Yii::$app->params['editLink'] = "vacancies/update?id=".$item->id;

            return $this->render('show', [
                'item' => $item,
                'items' => $items,
                'page' => $this->page,
            ]);
        }
        else{
            throw new \yii\web\NotFoundHttpException;
        }
    }
}
