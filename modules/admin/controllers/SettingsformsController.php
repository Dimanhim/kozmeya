<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class SettingsformsController extends \app\modules\admin\components\AdminController
{

    public $className = "app\models\\SettingsForms";
    public $status = "active";

    public function init(){
        $this->view->title = "Настройки модулей";

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

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $data = Json::decode($model->form_data, false);
        $post = Yii::$app->request->post();

        if (Yii::$app->request->isPost) {
            if(isset($post["FormData"])) $model->form_data = Json::encode($post["FormData"]);

            if($model->save()){
                $model->updateRelations();

                return (isset($_POST["updatenstay"]) && $_POST["updatenstay"] == 1 ? $this->redirect(['update', 'id' => $model->id]) : $this->redirect(['index']));
            }

        }

        return $this->render($model->code, [
            'model' => $model,
            'data' => $data,
        ]);
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
