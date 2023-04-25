<?php

namespace app\modules\admin\controllers;

use app\models\Subscribes;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class SubscribesController extends \app\modules\admin\components\AdminController
{

    public $className = "app\models\\Subscribes";
    public $status = "active";

    public function init(){
        $this->view->title = "Подписка";

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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isPost) {
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

    public function actionExport()
    {
        ini_set('memory_limit', '12000M');
        ini_set('max_execution_time', 999);

        $list = 0;

        $PHPExcel = new \PHPExcel();

        $PHPExcel->setActiveSheetIndex($list);

        $rows = Subscribes::find()->where([])->all();

        $PHPExcel->getActiveSheet()->setCellValue('A1', "ID");
        $PHPExcel->getActiveSheet()->setCellValue('B1', "E-mail")->getColumnDimension('B')->setWidth(30);
        $PHPExcel->getActiveSheet()->setCellValue('C1', "Дата");


        $PHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);

        $styleTH = new \PHPExcel_Style();
        $styleTH->applyFromArray(
            array('fill' 	=> array(
                'type'		=> \PHPExcel_Style_Fill::FILL_SOLID,
                'color'		=> array('argb' => 'FFCCFFCC')
            ),
                'borders' => array(
                    'bottom'	=> array('style' => \PHPExcel_Style_Border::BORDER_THIN),
                    'right'		=> array('style' => \PHPExcel_Style_Border::BORDER_MEDIUM)
                )
            ));

        $PHPExcel->getActiveSheet()->setSharedStyle($styleTH, "A1:C1");

        foreach($rows as $k=>$v){
            $line = $k+2;
            $PHPExcel->getActiveSheet()->setCellValue('A'.$line, $v->id);
            $PHPExcel->getActiveSheet()->setCellValue('B'.$line, $v->email);
            $PHPExcel->getActiveSheet()->setCellValue('C'.$line, date("d.m.Y H:i:s", strtotime($v->date)));
        }

        $PHPExcel->getActiveSheet()->setTitle('Экспорт');
        $PHPExcel->setActiveSheetIndex($list);




        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export-'.date("Y-m-d H:i:s").'.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $writer = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit();



        return $this->render('export', []);
    }
}
