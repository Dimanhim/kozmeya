<?php

namespace app\controllers;

use app\models\form\SiteLoginForm;
use app\models\Orders;
use app\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ProfileController extends \app\components\Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function behaviors() {
        return [
            'eauth' => [
                // required to disable csrf validation on OpenID requests
                'class' => \nodge\eauth\openid\ControllerBehavior::className(),
                'only' => ['eauth'],
            ],
        ];
    }

    public function init(){
        parent::init();

        \Yii::$app->functions->add2navi("Личный кабинет", "profile");
    }

    public function actionIndex()
    {
        $messages = [];

        if(\Yii::$app->siteuser->isGuest){
            return $this->redirect("/");
        }

        $model = Users::findOne(Yii::$app->siteuser->getId());

        if(!$model){
            return $this->redirect("/");
        }

        $order = false;

        if(isset($_GET["order"]) && $_GET["order"] != "") {
            $order = Orders::findOne((int) $_GET["order"]);
        }

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            if(isset($post["Users"]["password_hash"]) && $post["Users"]["password_hash"] != "") {
                if(isset($post["old_password"]) && $model->validatePassword($post["old_password"]) && isset($post["repeat_password"]) && $post["repeat_password"] == $post["Users"]["password_hash"]) {
                    $model->password_hash = Yii::$app->security->generatePasswordHash($post["Users"]["password_hash"]);
                }
                else {
                    $messages[] = "Пароли не совпадают";
                }
            }

            unset($post["Users"]["password_hash"]);
            unset($post["old_password"]);
            unset($post["repeat_password"]);


            if ($model->load($post) && $model->save()) {
                if($model->save()){
                    $messages[] = 'Данные сохранены';

                    $model->updateRelations();
                    \Yii::$app->functions->uploader($model, "avatar", "avatarUploader");

                    $model->save();

                    Yii::$app->siteuser->identity = $model;
                }
            }
            else {
                foreach($model->getErrors() as $error){
                    $messages = $messages + $error;
                }
            }
        }

        return $this->render('index', [
            'messages' => $messages,
            'model' => $model,
            'order' => $order,
        ]);
    }

    public function actionLogin()
    {
        $form = new SiteLoginForm();

        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $success = false;
            $msg = Yii::$app->langs->t("Добро пожаловать на сайт!");
            $error = Yii::$app->langs->t("Пользователь с таким логином и паролем не найден");

            $form = new SiteLoginForm();
            if ($form->load(\Yii::$app->request->post()) && $form->login()) {
                $success = true;
            }

            return ['success' => $success, 'msg' => $msg, 'error' => $error];
        }

        return $this->render('login', [
            'form' => $form,
        ]);
    }

    public function actionRegister()
    {
        if (Yii::$app->request->isAjax) {
            file_put_contents('info-log.txt', date('d.m.Y H:i:s').' - '.print_r('register', true)."\n", FILE_APPEND);
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $msg = Yii::$app->langs->t("Добро пожаловать на сайт!");

            $data = \Yii::$app->request->post();
            file_put_contents('info-log.txt', date('d.m.Y H:i:s').' data - '.print_r($data, true)."\n", FILE_APPEND);
            $result = Users::register($data);
            file_put_contents('info-log.txt', date('d.m.Y H:i:s').' result - '.print_r($result, true)."\n", FILE_APPEND);
            $success = $result["success"];
            $error = $result["error"];
            $response = ['success' => $success, 'msg' => $msg, 'error' => $error];
            file_put_contents('info-log.txt', date('d.m.Y H:i:s').' response - '.print_r($response, true)."\n", FILE_APPEND);
            return ['success' => $success, 'msg' => $msg, 'error' => $error];
        }

        return $this->render('register', [

        ]);
    }

    public function actionRepair()
    {

        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $success = false;
            $msg = "Пароль выслан вам на указанный E-mail!";
            $error = "Произошла ошибка";
            $data = \Yii::$app->request->post();

            if($model = Users::findByUsername(trim($data["email"]))) {
                $success = true;

                $password = $model->randomPassword();
                $model->password_hash = Yii::$app->security->generatePasswordHash($password);
                $model->save();

                Yii::$app->mailer->compose(['html' => 'text'],['text' => "Ваш логин: ".$model->username."<br>Ваш пароль: ".$password])
                    ->setTo($model->email)
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['HOST']])
                    ->setSubject("Восстановаление пароля" . " " . Yii::$app->params['HOST'])
                    ->send();
            }
            else {
                $error = "Пользователь с таким E-mail не существует";
            }

            return ['success' => $success, 'msg' => $msg, 'error' => $error];
        }

        return $this->render('repair', [

        ]);
    }

    public function actionEauth()
    {
        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
        if (isset($serviceName)) {
            /** @var $eauth \nodge\eauth\ServiceBase */
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('profile/eauth'));

            try {
                if ($eauth->authenticate()) {
//					var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes()); exit;

                    $identity = Users::findByEAuth($eauth);
                    Yii::$app->getUser()->login($identity);

                    // special redirect with closing popup window
                    $eauth->redirect();
                }
                else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            }
            catch (\nodge\eauth\ErrorException $e) {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: '.$e->getMessage());

                // close popup window and redirect to cancelUrl
//				$eauth->cancel();
                $eauth->redirect($eauth->getCancelUrl());
            }
        }
    }

    public function actionLogout()
    {
        Yii::$app->siteuser->logout();

        return $this->goHome();
    }
}
