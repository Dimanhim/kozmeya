<?php

namespace app\modules\admin\controllers;

use app\models\Brands;
use app\models\Categories;
use app\models\CategoriesToItems;
use app\models\Items;
use app\models\ItemsProps;
use app\models\ItemsStatus;
use app\models\ItemsVars;
use app\models\ItemsVarsValues;
use app\models\Props;
use app\models\PropsCategories;
use app\models\VarsShowtypes;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class SlidersController extends \app\modules\admin\components\AdminController
{
    public $className = "app\models\\Sliders";
    public $status = "active";

    public function init(){
        $this->view->title = "Мини-галереи";

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


        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if($model->save()) {
                \Yii::$app->functions->modelUploads($model);
                $model->updateRelations();
                $model->save();

                return (isset($_POST["updatenstay"]) && $_POST["updatenstay"] == 1 ? $this->redirect(['update', 'id' => $model->id]) : $this->redirect(['index']));
            }
        }

        return $this->render('/components/system/_update', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isPost) {
                \Yii::$app->functions->modelUploads($model);
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
