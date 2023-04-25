<?php

namespace app\modules\admin\controllers;

use app\models\Payments;
use app\models\Permissions;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class AdminusersController extends \app\modules\admin\components\AdminController
{

    public $className = "app\models\\AdminUsers";
    public $status = "active";

    public function init(){
        $this->view->title = "Пользователи сайта";

        parent::init();
    }

    public function actionIndex()
    {
        $searchClassName = str_replace("models", "models\search", $this->className)."Search";

        $searchModel = Yii::createObject([
            'class' => $searchClassName,
        ]);

        $query = Yii::$app->request->queryParams;

        if(\Yii::$app->user->identity->type != "root"){
            $query["nottype"] = "root";
        }

        if(\Yii::$app->user->identity->type == "user"){
            $query["AdminUsersSearch"]["id"] = \Yii::$app->user->identity->id;
        }

        $dataProvider = $searchModel->search($query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        if(\Yii::$app->user->identity->type == "user") {
            throw new \yii\web\ForbiddenHttpException;
        }

        $model = Yii::createObject([
            'class' => $this->className,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->password_hash != "") $model->setPassword($model->password_hash);
            if($model->save()){
                \Yii::$app->functions->modelUploads($model);
                $model->updateRelations();
                $model->save();
            }

            return (isset($_POST["updatenstay"]) && $_POST["updatenstay"] == 1 ? $this->redirect(['update', 'id' => $model->id]) : $this->redirect(['index']));
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(\Yii::$app->user->identity->type == "user" && $model->id != \Yii::$app->user->identity->id) {
            throw new \yii\web\ForbiddenHttpException;
        }

        $post = Yii::$app->request->post();

        if(isset($post["AdminUsers"]["password_hash"]) && $post["AdminUsers"]["password_hash"] == "") {
            unset($post["AdminUsers"]["password_hash"]);
        }

        if ($model->load($post) && $model->save()) {
            if(isset($post["AdminUsers"]["password_hash"]) && $post["AdminUsers"]["password_hash"] != "") $model->setPassword($post["AdminUsers"]["password_hash"]);
            if($model->save()){
                \Yii::$app->functions->modelUploads($model);
                $model->updateRelations();
                $model->save();
            }

            return (isset($_POST["updatenstay"]) && $_POST["updatenstay"] == 1 ? $this->redirect(['update', 'id' => $model->id]) : $this->redirect(['index']));
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(\Yii::$app->user->identity->type == "user" || $model->type == "root" || $model->root == 1) {
            throw new \yii\web\ForbiddenHttpException;
        }
        else {
            $model->delete();
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
