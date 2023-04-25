<?php

namespace app\modules\admin\controllers;

use app\models\Redirects;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class RedirectsController extends \app\modules\admin\components\AdminController
{
    public $className = "app\models\\Redirects";
    public $status = "active";

    public $classNameShort = "Redirects";
    public $uploadFolder = "Redirectsxls";

    public function init(){
        $this->view->title = "Редиректы";

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
            $model->updateRelations();

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
            $model->updateRelations();

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


    public function actionImport()
    {

        $form_msg = array();
        $available_formats = array("xls", "xlsx");
        $import_file = 0;
        $details = array(
            'insert' => 0,
            'update' => 0,
            'errors' => array()
        );


        if (isset($_FILES[$this->classNameShort]))
        {
            $fileName = $_FILES[$this->classNameShort]['name']['file'];
            $fileTmp = $_FILES[$this->classNameShort]['tmp_name']['file'];
            $issetFile = false;
            if($fileTmp != "") $issetFile = true;

            ini_set('memory_limit', '12000M');
            ini_set('max_execution_time', 999);

            if ($issetFile) {
                $aboutfile = pathinfo($fileName);
                if(in_array($aboutfile['extension'], $available_formats)){
                    $filename = time().".".$aboutfile['extension'];

                    if (!is_dir(\Yii::$app->basePath.'/'.Yii::$app->params['uploadDir'].'/'.$this->uploadFolder))
                    {
                        mkdir(\Yii::$app->basePath.'/'.Yii::$app->params['uploadDir'].'/'.$this->uploadFolder, 0777);
                    }

                    $resultFileName = \Yii::$app->basePath.'/'.Yii::$app->params['uploadDir'].'/'.$this->uploadFolder.'/'.$filename;

                    if (move_uploaded_file($fileTmp, $resultFileName)  )
                    {
                        $reader = \PHPExcel_IOFactory::createReaderForFile($resultFileName);
                        $reader->setReadDataOnly(true);
                        $xls = $reader->load($resultFileName);

                        $xls->setActiveSheetIndex(0);
                        $sheet = $xls->getActiveSheet();

                        foreach($sheet->getRowIterator() as $line => $row)
                        {
                            if ($line >= 2)
                            {
                                $cellIterator = $row->getCellIterator();
                                $cellData 	  = array();

                                $pcNum = "";
                                foreach ($cellIterator as $cell)
                                {
                                    $cvalue = trim($cell->getCalculatedValue());
                                    $pcNum .= $cvalue;
                                    $cellData[$cell->getColumn()] = $cvalue;
                                }


                                if(isset($cellData['A']) && $cellData['A'] != ""){
                                    $url = str_replace(Yii::$app->params['HOST'], "", $cellData['A']);
                                    $redirect_url = str_replace(Yii::$app->params['HOST'], "", $cellData['B']);

                                    if($url != "" && $redirect_url != "" && $url != $redirect_url){
                                        $model = Yii::createObject([
                                            'class' => $this->className,
                                        ]);

                                        $model = $model->findOne(["url" => $url]);

                                        if($model){
                                            if(isset($cellData['B'])) $model->redirect_url = $redirect_url;
                                            if($model->save()) {
                                                $details['update']++;
                                            }
                                        }
                                        else {
                                            $model = Yii::createObject([
                                                'class' => $this->className,
                                            ]);

                                            $model->url = $url;

                                            if(isset($cellData['B'])) $model->redirect_url = $redirect_url;

                                            if($model->save()) {
                                                $details['insert']++;
                                            }
                                        }
                                    }

                                }

                            }
                        }

                        $import_file = 1;
                        $form_msg['msg'][] = array(
                            'value' => 'Файл загружен',
                            'type' => 'success',
                        );
                    }
                    else {
                        $form_msg['msg'][] = array(
                            'value' => 'Ошибка загрузки файла',
                            'type' => 'error',
                        );
                    }
                }
                else {
                    $form_msg['msg'][] = array(
                        'value' => 'Формат файла '.$aboutfile['extension'].' не поддерживается. Только '.implode(", ", $available_formats),
                        'type' => 'error',
                    );
                }

            }
            else {
                $form_msg['msg'][] = array(
                    'value' => 'Файл не загружен',
                    'type' => 'error',
                );
            }

        }

        return $this->redirect('/admin/'.Yii::$app->controller->id.'/index?'.http_build_query($form_msg).'&import_file='.$import_file.'&update='.$details['update']."&insert=".$details['insert']);
    }

    public function actionExport()
    {
        ini_set('memory_limit', '12000M');
        ini_set('max_execution_time', 999);

        $list = 0;

        $PHPExcel = new \PHPExcel();

        $PHPExcel->setActiveSheetIndex($list);

        $rows = Redirects::find()->where([])->all();

        $PHPExcel->getActiveSheet()->setCellValue('A1', "URL")->getColumnDimension('B')->setWidth(30);
        $PHPExcel->getActiveSheet()->setCellValue('B1', "Redirect URL")->getColumnDimension('B')->setWidth(30);
        $PHPExcel->getActiveSheet()->setCellValue('C1', "Код");


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
            $PHPExcel->getActiveSheet()->setCellValue('A'.$line, $v->url);
            $PHPExcel->getActiveSheet()->setCellValue('B'.$line, $v->redirect_url);
            $PHPExcel->getActiveSheet()->setCellValue('C'.$line, $v->code);
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
