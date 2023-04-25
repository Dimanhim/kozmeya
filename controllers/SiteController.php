<?php

namespace app\controllers;

use app\models\AdminUsers;
use app\models\Categories;
use app\models\Faces;
use app\models\form\SiteLoginForm;
use app\models\Items;
use app\models\News;
use app\models\Orders;
use app\models\Partners;
use app\models\Reviews;
use app\models\Slider;
use app\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\StaticPage;

class SiteController extends \app\components\Controller
{
	public $viewLayout = "static"; 
	public $viewData = [];

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
        $slider = Slider::find()->where("vis = '1'")->orderBy("posled")->all();

        return $this->render('index', [
            'slider' => $slider,
        ]);
    }
	
	public function actionStatic()
    {
		$alias = Yii::$app->request->queryParams['alias'];
		
		$model = new StaticPage();
		$page = $model->findOne(['alias' => $this->lastAlias]);
		if($page) {
            //Yii::$app->functions->validateUrl(trim(\Yii::$app->functions->hierarchyUrl($page), "/"));

            \Yii::$app->functions->add2navihierarchy($page);

            $parent = [];
            \Yii::$app->functions->forceParent($page, $parent);

            if(file_exists(Yii::getAlias('@app/views/site/').$page->alias.".php")) $this->viewLayout = $page->alias;


            if($parent->viewtochilds) {
                if($parent->view != "") $this->viewLayout = $parent->view;
                if($parent->module != "") $this->{'proccess'.$parent->module}();
            }
            else {
                if($page->view != "") $this->viewLayout = $page->view;
                if($page->module != "") $this->{'proccess'.$page->module}();
            }

            $this->viewData['parent'] = $parent;

            \Yii::$app->params['editLink'] = "static/update?id=".$page->id;
		}
        elseif($alias == "search"){
            $this->viewLayout = "search";
            \Yii::$app->functions->add2navi("Поиск", $alias);
            $s = Yii::$app->request->queryParams["s"];

            $sections = Yii::$app->functions->searcher($s);

            $this->viewData["sections"] = $sections;
        }
		else throw new \yii\web\NotFoundHttpException;

        $this->viewData['page'] = $page;

        \Yii::$app->params['currentPage'] = $page;

        return $this->render($this->viewLayout, $this->viewData);
		exit();
    }

    /* proccesses */
    public function proccessHello(){
        $this->viewData["hello"] = true;
    }
}
