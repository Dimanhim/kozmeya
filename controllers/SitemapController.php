<?php

namespace app\controllers;

use app\models\StaticPage;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class SitemapController extends \app\components\Controller
{
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

        \Yii::$app->functions->add2navi('Карта сайта', 'karta-sayta');
    }

    public function actionIndex()
    {
        $sitemap = $this->siteMap();

        return $this->render('index', [
            'result' => $sitemap['elements'],
        ]);
    }

}
