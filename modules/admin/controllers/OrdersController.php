<?php

namespace app\modules\admin\controllers;

use app\models\Items;
use app\models\Orders;
use app\models\OrdersComments;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use kartik\mpdf\Pdf;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class OrdersController extends \app\modules\admin\components\AdminController
{

    public $className = "app\models\\Orders";
    public $status = "active";

    public function init(){
        $this->view->title = "Заказы";

        parent::init();
    }

    public function actionIndex()
    {
        $searchClassName = str_replace("models", "models\search", $this->className)."Search";

        $searchModel = Yii::createObject([
            'class' => $searchClassName,
        ]);

        $query = Yii::$app->request->queryParams;
        $query[\Yii::$app->functions->getModelName($searchModel)]["deleted"] = 0;

        $dataProvider = $searchModel->search($query);

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

                \Yii::$app->catalog->sendOrderMail($model);
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isPost) {
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
        $model->deleted = 1;
        $model->save();

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

    public function actionOrderpaymentlink()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = Yii::$app->request->post();

        $success = false;
        $msg = "Ссылка на оплату отправлена!";
        $error = "Ошибка";

        $model = $this->findModel($data["id"]);
        $link = \Yii::$app->kassa->generateLink($model);

        if(Yii::$app->mailer->compose(['html' => 'order'],['model' => $model, 'text' => 'Ваша ссылка на оплату заказ - <a href="'.$link.'">Оплатить</a>'])
            ->setTo(explode(",", $model->email))
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['HOST']])
            ->setSubject("Заказ №".$model->id." ссылка на оплату!")
            ->send()){
            $success = true;
        }

        return ['success' => $success, 'msg' => $msg, 'error' => $error, 'link' => $link];
    }

    public function actionReinitprice()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = Yii::$app->request->post();

        $success = false;
        $msg = "Удалено";
        $error = "Ошибка";

        $items = "";
        $prices = "";

        $model = new \stdClass();
        foreach($data["Orders"] as $field => $value){
            $model->{$field} = $value;
        }

        $model->items = new \stdClass();

        if(isset($data["OrdersItems"]["id"])) {
            foreach($data["OrdersItems"]["id"] as $index => $id){
                if(isset($data["OrdersItems"]["item_id"][$index]) && $data["OrdersItems"]["item_id"][$index] != "" && $data["OrdersItems"]["item_id"][$index] != 0) {
                    $model->items->{$index} = new \stdClass();
                    $model->items->{$index}->id = ($id == "" ? 0 : $id);
                    $model->items->{$index}->item_id = $data["OrdersItems"]["item_id"][$index];
                    $model->items->{$index}->name = $data["OrdersItems"]["name"][$index];
                    $model->items->{$index}->price = $data["OrdersItems"]["price"][$index];
                    $model->items->{$index}->qty = $data["OrdersItems"]["qty"][$index];
                    $model->items->{$index}->item = Items::findOne($model->items->{$index}->item_id);

                    $items .= $this->renderPartial("/orders/_items", ['item' => $model->items->{$index}, 'edit' => true]);
                }
            }
        }

        $prices = $this->renderPartial("/orders/_prices", ['model' => $model,]);

        return ['success' => $success, 'msg' => $msg, 'error' => $error, 'items' => $items, 'prices' => $prices];
    }

    public function actionAddcomment()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = Yii::$app->request->post();

        $success = false;
        $msg = "Комментарий добавлен";
        $error = "Ошибка";

        $html = "";

        if(isset($data["order_id"], $data["text"]) && $data["order_id"] != "" && trim($data["text"]) != ""){
            $model = new OrdersComments();
            $model->order_id = $data["order_id"];
            $model->text = $data["text"];
            $model->user_id = \Yii::$app->user->identity->getId();
            $model->date = date("Y-m-d H:i:s");

            if($model->save()){
                $success = true;
                $html = $this->renderPartial("/orders/_comment_item", ['comment' => $model]);
            }
        }



        return ['success' => $success, 'msg' => $msg, 'error' => $error, 'html' => $html];
    }

    public function actionExport()
    {
        ini_set('memory_limit', '12000M');
        ini_set('max_execution_time', 999);

        $list = 0;

        $PHPExcel = new \PHPExcel();

        $PHPExcel->setActiveSheetIndex($list);

        $orders = Orders::find()->where(["deleted" => 0])->orderBy("orders.date DESC")->all();

        $PHPExcel->getActiveSheet()->setCellValue('A1', "ID");
        $PHPExcel->getActiveSheet()->setCellValue('B1', "Дата")->getColumnDimension('B')->setWidth(30);
        $PHPExcel->getActiveSheet()->setCellValue('C1', "Информация о заказе")->getColumnDimension('C')->setWidth(50);
        $PHPExcel->getActiveSheet()->setCellValue('D1', "Данные покупателя")->getColumnDimension('D')->setWidth(50);
        $PHPExcel->getActiveSheet()->setCellValue('E1', "Состав заказа")->getColumnDimension('E')->setWidth(70);
        $PHPExcel->getActiveSheet()->setCellValue('F1', "Итого")->getColumnDimension('F')->setWidth(70);;

        $PHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

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

        $PHPExcel->getActiveSheet()->setSharedStyle($styleTH, "A1:F1");

        foreach($orders as $k=>$v){
            $line = $k+2;

            $htmlHelper = new \PHPExcel_Helper_HTML();

            $PHPExcel->getActiveSheet()->setCellValue('A'.$line, $v->id);
            if(isset($_GET["delivery"])) {
                $PHPExcel->getActiveSheet()->setCellValue('B'.$line, date("d.m.Y H:i:s", strtotime($v->delivery_date)).($v->delivery_time_range != ", время доставки - ".$v->delivery_time_range ? : "").($v->delivery_time != "00:00:00" ? ", к точному времени - ".$v->delivery_time : ""));
            }
            else {
                $PHPExcel->getActiveSheet()->setCellValue('B'.$line, date("d.m.Y H:i:s", strtotime($v->date)));
            }

            $PHPExcel->getActiveSheet()->setCellValue('C'.$line, "Статус: ".$v->status->name."\r\nТип доставки: ".$v->delivery->name."\r\nМетод оплаты: ".$v->payment->name."\r\n");
            $PHPExcel->getActiveSheet()->getStyle('C'.$line)->getAlignment()->setWrapText(true);

            $PHPExcel->getActiveSheet()->setCellValue('D'.$line, "Ф.И.О.: ".$v->name."\r\nТелефон: ".$v->phone."\r\nE-mail: ".$v->email."\r\nАдрес: ".$v->address."\r\n");
            $PHPExcel->getActiveSheet()->getStyle('D'.$line)->getAlignment()->setWrapText(true);

            $orderitems = "";
            foreach($v->items as $kk=>$vv){
                $orderitems .= mb_convert_encoding(html_entity_decode($this->renderPartial("/orders/_items", ['item' => $vv, 'edit' => false, 'br' => true])), 'HTML-ENTITIES', 'UTF-8');
            }

            $PHPExcel->getActiveSheet()->setCellValue('E'.$line, $htmlHelper->toRichTextObject($orderitems));
            $PHPExcel->getActiveSheet()->getStyle('E'.$line)->getAlignment()->setWrapText(true);

            $PHPExcel->getActiveSheet()->setCellValue('F'.$line, $htmlHelper->toRichTextObject(mb_convert_encoding(html_entity_decode($this->renderPartial("/orders/_prices", ['model' => $v, 'br' => true])), 'HTML-ENTITIES', 'UTF-8')));
            $PHPExcel->getActiveSheet()->getStyle('F'.$line)->getAlignment()->setWrapText(true);

            $PHPExcel->getActiveSheet()->getRowDimension($line)->setRowHeight(100);
        }

        $PHPExcel->getActiveSheet()->setTitle('Экспорт заказов');
        $PHPExcel->setActiveSheetIndex($list);




        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="orders-'.date("Y-m-d H:i:s").'.xlsx"');
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

    public function actionBill()
    {
        $data = Yii::$app->request->get();

        $model = Orders::findOne($data["id"]);

        $this->layout = false;
        $content = $this->renderPartial("bill", ['model' => $model]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@app/modules/admin/css/_system/orders/bill.css',
            // any css to be embedded if required
            //'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            //'options' => ['title' => 'Krajee Report Title'],
            // call mPDF methods on the fly
            'methods' => [
                //'SetHeader'=>['Krajee Report Header'],
                //'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }

    public function actionDeliveries()
    {
        $searchClassName = str_replace("models", "models\search", $this->className)."Search";

        $searchModel = Yii::createObject([
            'class' => $searchClassName,
        ]);

        $query = Yii::$app->request->queryParams;
        $query[\Yii::$app->functions->getModelName($searchModel)]["deleted"] = 0;
        $query[\Yii::$app->functions->getModelName($searchModel)]["delivery_id"] = 2;
        $query[\Yii::$app->functions->getModelName($searchModel)]["date"] = date("Y-m-d");

        $dataProvider = $searchModel->search($query);

        return $this->render('delivery', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}