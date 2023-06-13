<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;

class AjaxController extends \app\modules\admin\components\AdminController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public $postData = [];
    public $getData = [];

    public function init(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException;
        }

        $this->postData = Yii::$app->request->post();
        $this->getData = Yii::$app->request->get();

        $this->enableCsrfValidation = false;

        parent::init();
    }

    /* Photos */
    public function actionDeletefile()
    {
        $fileData = explode("::", $this->postData["key"]);

        $className =  "app\models\\".$fileData[0];

        $model = new $className();

        $row = $model->findOne($fileData[1]);

        $row->{$fileData[3]} = str_replace($fileData[2].";", "", $row->{$fileData[3]});

        if($row->save()){

        }

        return [];
    }

    public function actionModelloadfile(){
        $success = false;
        $error = '';
        $msg = 'Файл загружен';

        $files = [];
        $html = "";

        $targetPath = \Yii::$app->basePath.'/'.Yii::$app->params['uploadDir'].'/'.$this->postData["folder"];
        if (!is_dir($targetPath))
        {
            @mkdir($targetPath, 0777);
        }

        if (isset($_FILES['filesLoader']['name']) && is_array($_FILES['filesLoader']['name'])) {

            foreach($_FILES['filesLoader']['name'] as $index=>$name){
                $tmp = $_FILES['filesLoader']['tmp_name'][$index];

                $salt = rand(0, 9999);
                while (true) {
                    $aboutFile = pathinfo($name);
                    $name = sha1($salt.$aboutFile['filename']).'.'.$aboutFile['extension'];
                    if (!file_exists($targetPath."/".$name)) break;
                }

                if(move_uploaded_file($tmp, $targetPath.'/'.$name )) {
                    $files[] = $targetPath.'/'.$name;

                    $v = array("file" => $name, "caption" => "/".Yii::$app->params['uploadDir']."/".$this->postData["methodSize"].$this->postData["folder"]."/".$name, "text" => "");

                    // он здесь сохраняет оригинал в sitefiles/Items... Это делает move_uploaded_file

                    // здесь после рендеринга сохраняет в папку 255/255
                    // тут по ходу нужно просто methodSize поменять на 1500 и все
                    $html .= $this->renderPartial( '/components/uploader_custom/parts/item', ['field' => $this->postData["field"], 'v' => $v] );
                    $success = true;
                }
            }
        }

        return ['html' => $html,'files' => $files, 'success' => $success, 'error' => $error, 'msg' => $msg];
    }

    /* Photos */


    public function actionMindsearch(){
        $model = Yii::createObject([
            'class' => $this->getData["model"],
        ]);

        $sql = "";

        $s = preg_replace('/\s\s+/', ' ', trim($this->getData["q"]));
        $sFormatter = Yii::$app->functions->latToCyr($s);

        if ($searchfields = Json::decode($this->getData["searchfields"]))
        {
            foreach ($searchfields as $searchfield)
            {
                $sql .= " `".$searchfield."` LIKE ".Yii::$app->db->quoteValue("%".$s."%")." OR ";
                $sql .= " `".$searchfield."` LIKE ".Yii::$app->db->quoteValue("%".$sFormatter."%")." OR ";
            }

            $sql = rtrim($sql, 'OR ');
        }

        $fields = Json::decode($this->getData["fields"]);

        $rows = $model::find()->where($sql)->select($fields)->orderBy(end($fields))->all();


        return ['results' => $rows];
    }

    public function actionFastchange()
    {
        $success = false;
        $msg = "Сохранено";
        $error = "Ошибка";

        if (isset($this->postData['id'], $this->postData['model'], $this->postData['field'])) {
            $model = Yii::createObject([
                'class' => $this->postData["model"],
            ]);

            $row = $model->findOne($this->postData['id']);

            if($row){
                $fields = explode("|",$this->postData['field'] );
                $values = explode("|",$this->postData['value'] );

                foreach($fields as $key=>$field) {
                    $row->{$field} = $values[$key];
                }

                if($row->save()){
                    $success = true;
                }
            }
        }

        return ['success' => $success, 'msg' => $msg, 'error' => $error, 'status' => $this->postData['value']];
    }

    public function actionRemoveall()
    {
        $success = false;
        $msg = "Удалено";
        $error = "Ошибка";

        if (isset($this->postData['selection'], $this->postData['model']) && count($this->postData['selection']) > 0) {
            $model = Yii::createObject([
                'class' => $this->postData["model"],
            ]);

            if($model->deleteAll(['IN', 'id', $this->postData['selection']])){
                $success = true;
            }
        }

        return ['success' => $success, 'msg' => $msg, 'error' => $error];
    }
}
