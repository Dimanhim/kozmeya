<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CustomfieldsController extends \app\modules\admin\components\AdminController
{

    public $className = "app\models\\CustomFields";
    public $status = "active";

    public function init(){
        $this->view->title = "Произвольные поля";

        parent::init();
    }

    public function actionIndex()
    {
        $searchClassName = str_replace("models", "models\search", $this->className)."Search";

        $searchModel = Yii::createObject([
            'class' => $searchClassName,
        ]);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = Yii::createObject([
            'class' => $this->className,
        ]);

        if(isset($_GET["class"])) {
            $model->class =  $_GET["class"];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isPost) {
                $model->updateRelations();
                $model->save();
            }

            //return $this->redirect(['index']);
            if(isset($_GET["return_id"])) {
                return $this->redirect([strtolower(($_GET["class"] == "StaticPage" ? "Static" : $_GET["class"])).'/update', 'id' => $_GET["return_id"]]);
            }
            else {
                return $this->redirect(['update', 'id' => $model->id]);
            }
        } else {
            return $this->render('/components/system/_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isPost) {
                $model->updateRelations();
                $model->save();
            }

            //return $this->redirect(['index']);
            if(isset($_GET["return_id"])) {
                return $this->redirect([strtolower(($model->class == "StaticPage" ? "Static" : $model->class)).'/update', 'id' => $_GET["return_id"]]);
            }
            else {
                return $this->redirect(['update', 'id' => $model->id]);
            }
        } else {
            return $this->render('/components/system/_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        if(isset($_GET["return_id"])) {
            return $this->redirect([strtolower(($model->class == "StaticPage" ? "Static" : $model->class)).'/update', 'id' => $_GET["return_id"]]);
        }
        else {
            return $this->redirect(['index']);
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        $model = Yii::createObject([
            'class' => $this->className,
        ]);

        if (($model = $model->findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
