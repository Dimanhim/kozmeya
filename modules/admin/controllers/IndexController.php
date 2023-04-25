<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\form\LoginForm;

class IndexController extends \app\modules\admin\components\AdminController
{
    public $status = "active";

    public function init(){
        $this->view->title = "Главная страница";

        parent::init();
    }

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction'],
        ];
    }

    public function actionIndex()
    {
        $this->title = "Dashboard";

        return $this->render('index');
    }

    public function actionLogin()
    {
        $this->layout = '@app/modules/admin/views/layouts/login';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
