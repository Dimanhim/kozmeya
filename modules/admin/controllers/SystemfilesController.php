<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */

class SystemfilesController extends \app\modules\admin\components\AdminController
{

    public $className = "app\models\\SystemFiles";
    public $status = "active";

    public function init(){
        $this->view->title = "Системные файлы";

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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isPost) {
                if($model->content != "") {
                    if($model->path == "") $model->path = "/";
                    file_put_contents(Yii::$app->params['PATH'].$model->path.$model->name, $model->content);
                }
                $model->updateRelations();
                $model->save();
            }

            return (isset($_POST["updatenstay"]) && $_POST["updatenstay"] == 1 ? $this->redirect(['update', 'id' => $model->id]) : $this->redirect(['index']));
        } else {
            return $this->render('/components/system/_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(is_file(Yii::$app->params['PATH'].$model->path.$model->name)) {
            $model->content = file_get_contents(Yii::$app->params['PATH'].$model->path.$model->name);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isPost) {
                if($model->content != "") {
                    if($model->path == "") $model->path = "/";
                    file_put_contents(Yii::$app->params['PATH'].$model->path.$model->name, $model->content);
                }
                $model->updateRelations();
                $model->save();
            }

            return (isset($_POST["updatenstay"]) && $_POST["updatenstay"] == 1 ? $this->redirect(['update', 'id' => $model->id]) : $this->redirect(['index']));
        } else {
            return $this->render('/components/system/_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

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
