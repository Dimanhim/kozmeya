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
use app\models\VarsShowtypesValues;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;

class ItemsController extends \app\modules\admin\components\AdminController
{
    public $className = "app\models\\Items";
    public $status = "active";

    public function init(){
        $this->view->title = "Товары";

        parent::init();
    }

    public function actionIndex()
    {
        $get = Yii::$app->request->get();

        if(isset($get["filters"])) {
            $get["view"] = 2;
        }

        if(isset($get["view"])) {
            \Yii::$app->session["items_catalog_view"] = $get["view"];
        }

        $get["view"] = \Yii::$app->session["items_catalog_view"];

        $view = (\Yii::$app->session->has("items_catalog_view") ? \Yii::$app->session["items_catalog_view"] : 1);

        $searchClassName = str_replace("models", "models\search", $this->className)."Search";

        $searchModel = Yii::createObject([
            'class' => $searchClassName,
        ]);

        $this->filtersData = (isset($_GET["filters"]) ? $this->filtersData + $_GET["filters"] : $this->filtersData);

        $searchModel->filtersData = $this->filtersData;

        if($view == 2) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'view' => $view,
            ]);
        }
        else {
            $category = [];
            $categories = Categories::hierarchy();
            if(isset($get["category_id"]) && $get["category_id"] != 0) {
                $category = Categories::findOne($get["category_id"]);
                $categories = ($category && isset($categories[$category->id]) ? $categories[$category->id] : []);
            }
            else {
                $categories = (isset($categories[0]) ? $categories[0] : []);
            }

            return $this->render('indexevo', [
                'searchModel' => $searchModel,
                'categories' => $categories,
                'category' => $category,
                'view' => $view,
            ]);
        }

    }

    public function actionCreate()
    {
        $model = Yii::createObject([
            'class' => $this->className,
        ]);

        if(isset($_GET["cloneid"])) {
            $model = $model->find()->where(["id" => $_GET["cloneid"]])->one();
            $model->alias = "";
        }

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->isNewRecord = true;
            unset($model->id);

            if($model->save()) {
                \Yii::$app->functions->modelUploads($model);
                $model->updateRelations();
                $model->save();

                return (isset($_POST["updatenstay"]) && $_POST["updatenstay"] == 1 ? $this->redirect(['update', 'id' => $model->id]) : $this->redirect(['index']));
            }
        }

        return $this->render('update', [
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
            return $this->render('update', [
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

    public function actionPrices()
    {
        $data = Yii::$app->request->post();

        $model = Yii::createObject([
            'class' => $this->className,
        ]);

        $msg = [];

        if(isset($data["Prices"])) {
            if(isset($data["Prices"]["value"]) && $data["Prices"]["value"] != "") {
                $value = $data["Prices"]["value"];

                $where = "items.vis = '1' AND categories.id IS NOT NULL";

                if(isset($data["Prices"]["categories"]) && count($data["Prices"]["categories"]) > 0) $where .= " AND categories.id IN (".implode(",", $data["Prices"]["categories"]).") ";
                if(isset($data["Prices"]["brands"]) && count($data["Prices"]["brands"]) > 0) $where .= " AND items.brand_id IN (".implode(",", $data["Prices"]["brands"]).") ";
                if(isset($data["Prices"]["items"]) && $data["Prices"]["items"] != "") $where .= " AND items.id IN (".$data["Prices"]["items"].") ";
                if((isset($data["Prices"]["price_from"]) && $data["Prices"]["price_from"] != "") || (isset($data["Prices"]["price_to"]) && $data["Prices"]["price_to"] != "")) {
                    if($data["Prices"]["price_from"] != "") $where .= " AND items.price >= '".$data["Prices"]["price_from"]."' ";
                    if($data["Prices"]["price_to"] != "") $where .= " AND items.price <= '".$data["Prices"]["price_to"]."' ";
                }

                $ids = yii\helpers\ArrayHelper::map(Items::find()->joinWith("categories")->where($where)->all(), 'id', 'id');

                if(count($ids) > 0) {
                    if($data["Prices"]["type"] == 1) {
                        if($value > 0) {
                            \Yii::$app->db->createCommand("UPDATE items SET price = price*((100+".abs($value).") / 100) WHERE id IN (".implode(",", $ids).");")->execute();
                        }
                        else {
                            \Yii::$app->db->createCommand("UPDATE items SET price = price*((100-".abs($value).") / 100) WHERE id IN (".implode(",", $ids).");")->execute();
                        }
                    }
                    else {
                        if($value > 0) {
                            \Yii::$app->db->createCommand("UPDATE items SET price = price + ".abs($value)." WHERE id IN (".implode(",", $ids).");")->execute();
                        }
                        else {
                            \Yii::$app->db->createCommand("UPDATE items SET price = price - ".abs($value)." WHERE id IN (".implode(",", $ids).");")->execute();
                        }
                    }

                    $msg[] = ["type" => "success", "msg" => "Цены изменены!", "description" => "Обновлено ".count($ids)." товар(ов)"];
                }
                else {
                    $msg[] = ["type" => "error", "msg" => "Цены не изменены", "description" => "Нет товаров подходящих под условия"];
                }
            }
            else {
                $msg[] = ["type" => "error", "msg" => "Цены не изменены", "description" => "Не задано значение изменения цен"];
            }
        }


        return $this->render('prices', [
            'model' => $model,
            'msg' => $msg,
        ]);
    }


    public function actionImport()
    {
        ini_set('memory_limit', '12000M');
        ini_set('max_execution_time', 999);

        $form_msg = array();
        $available_formats = array("xls", "xlsx");
        $import_file = 0;
        $details = array(
            'insert' => 0,
            'update' => 0,
            'errors' => array()
        );



        if (isset($_POST["Import"]["category_id"]) && isset($_FILES["Import"]))
        {
            $category = Categories::findOne($_POST["Import"]["category_id"]);
            $varShowTypes = VarsShowtypes::find()->all();
            $showtypes = [];
            foreach($varShowTypes as $k=>$v){
                $showtypes[mb_strtolower($v->name, "utf-8")] = $v->id;
            }

            $fileName = $_FILES["Import"]['name']['file'];
            $fileTmp = $_FILES["Import"]['tmp_name']['file'];
            $issetFile = false;
            if($fileTmp != "") $issetFile = true;

            if ($issetFile) {
                $aboutfile = pathinfo($fileName);
                if(in_array($aboutfile['extension'], $available_formats)){
                    $reader = \PHPExcel_IOFactory::createReaderForFile($fileTmp);
                    $reader->setReadDataOnly(true);
                    $xls = $reader->load($fileTmp);

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
                                $cellData[$cell->getColumn()] = trim($cvalue);
                            }


                            if(isset($cellData['A']) && $cellData['A'] != ""){
                                $newRow = false;

                                $model = Yii::createObject([
                                    'class' => $this->className,
                                ]);

                                $model = $model->findOne(["id" => $cellData['A']]);
                                if(!$model) {
                                    $model = Yii::createObject([
                                        'class' => $this->className,
                                    ]);

                                    $newRow = true;
                                }



                                $labels = ["new" => 0, "special" => 0, "day_item" => 0];
                                if(isset($cellData['H'])) {
                                    $labelsData = explode(";", $cellData['H']);
                                    $labels["new"] = $labelsData[0];
                                    $labels["special"] = $labelsData[1];
                                    $labels["day_item"] = $labelsData[2];
                                }

                                if(isset($cellData['A'])) $model->id = $cellData['A'];
                                if(isset($cellData['B'])) $model->name = $cellData['B'];
                                if(isset($cellData['C'])) $model->images = $cellData['C'];
                                if(isset($cellData['E'])) $model->price = \Yii::$app->functions->onlyNumbers($cellData['E']);
                                if(isset($cellData['G'])) $model->percent = $cellData['G'];

                                if(isset($cellData['D'])) {
                                    if(!$brand = Brands::findOne(["name" => $cellData['D']])){
                                        $brand = new Brands();
                                        $brand->name = $cellData['D'];
                                        $brand->country_id = 1;
                                        $brand->save();
                                    }

                                    $model->brand_id = $brand->id;
                                }

                                if(isset($cellData['I'])) {
                                    if($status = ItemsStatus::findOne(["name" => $cellData['I']])){
                                        $model->status_id = $status->id;
                                    }
                                }

                                if(isset($cellData['J'])) $model->vis = $cellData['J'];

                                foreach($labels as $labelfield => $labelvalue) {
                                    $model->{$labelfield} = $labelvalue;
                                }

                                if($model->save()) {
                                    \Yii::$app->db->createCommand()->delete('categoriestoitems', ['item_id' => $model->id,])->execute();

                                    $hierarchy = [];
                                    Yii::$app->catalog->hierarchy($category, $hierarchy);

                                    foreach ($hierarchy as $k=>$v){
                                        $categoriestoitems = new CategoriesToItems();
                                        $categoriestoitems->category_id = $v->id;
                                        $categoriestoitems->item_id = $model->id;
                                        $categoriestoitems->save();
                                    }


                                    \Yii::$app->db->createCommand()->delete('items_props', ['item_id' => $model->id,])->execute();

                                    if(isset($cellData['K']) && $cellData['K'] != "") {
                                        $props = array_filter(explode(";", $cellData['K']));

                                        foreach($props as $k=>$v) {
                                            $propsData = array_filter(explode("=", $v));
                                            if(isset($propsData[0]) && isset($propsData[1]) && $propsData[0] != "" && $propsData[1] != ""){
                                                if(!$prop = Props::findOne(["name" => $propsData[0]])){
                                                    $prop = new Props();
                                                    $prop->name = $propsData[0];
                                                    if($prop->save()){
                                                        $propscategories = new PropsCategories();
                                                        $propscategories->category_id = $category->id;
                                                        $propscategories->prop_id = $prop->id;
                                                        $propscategories->save();
                                                    }
                                                }

                                                $itemsprops = new ItemsProps();
                                                $itemsprops->prop_id = $prop->id;
                                                $itemsprops->item_id = $model->id;
                                                $itemsprops->value = $propsData[1];
                                                $itemsprops->save();
                                            }

                                        }
                                    }

                                    \Yii::$app->db->createCommand()->delete('items_vars', ['item_id' => $model->id,])->execute();

                                    if(isset($cellData['L']) && $cellData['L'] != "") {
                                        $vars = array_filter(explode(";", $cellData['L']));
                                        foreach($vars as $k=>$v) {
                                            $varsData = array_filter(explode("=", $v));
                                            if(isset($varsData[0]) && isset($varsData[1])) {
                                                $varName = $varsData[0];

                                                if(isset($showtypes[mb_strtolower($varName, "utf-8")])) {
                                                    $varValues = array_filter(explode("|", $varsData[1]));
                                                    $showtype_id = $showtypes[mb_strtolower($varName, "utf-8")];

                                                    if(count($varValues) > 0) {
                                                        if(!$varModel = ItemsVars::findOne(["name" => $varName, "item_id" => $model->id])){
                                                            $varModel = new ItemsVars();
                                                            $varModel->name = $varName;
                                                            $varModel->item_id = $model->id;
                                                            $varModel->showtype_id = $showtype_id;
                                                            $varModel->vis = 1;
                                                            $varModel->posled = 999;
                                                            $varModel->save();
                                                        }

                                                        foreach($varValues as $varValue){
                                                            $varValueName = $varValue;
                                                            $varValuePrice = 0;
                                                            if(preg_match("/(.+?)\(((\d+):(\d+))\)/", $varValue, $varValueData)) {
                                                                $varValueName = $varValueData[1];
                                                                $varValuePrice = $varValueData[3];
                                                            }

                                                            if(!$varvaluesmodel = ItemsVarsValues::findOne(["name" => $varValueName, "var_id" => $varModel->id])){
                                                                $varvaluesmodel = new ItemsVarsValues();
                                                            }

                                                            $var_value_id = 0;

                                                            if($varvalue = VarsShowtypesValues::find()->where(["LIKE", "text", $varValueName])->andWhere(["showtype_id" => $showtype_id])->one()){
                                                                $var_value_id = $varvalue->id;
                                                            }

                                                            $varvaluesmodel->var_id = $varModel->id;
                                                            $varvaluesmodel->var_value_id = $var_value_id;
                                                            $varvaluesmodel->name = $varValueName;
                                                            $varvaluesmodel->price = $varValuePrice;
                                                            $varvaluesmodel->vis = 1;
                                                            $varvaluesmodel->posled = 999;
                                                            $varvaluesmodel->save();
                                                        }
                                                    }
                                                }
                                            }


                                        }
                                    }

                                    if($newRow) {
                                        $details['insert']++;
                                    }
                                    else {
                                        $details['update']++;
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

            return $this->redirect('/admin/'.Yii::$app->controller->id.'/import?'.http_build_query($form_msg).'&import_file='.$import_file.'&update='.$details['update']."&insert=".$details['insert']);
        }

        return $this->render('import', []);
    }

    public function actionExport()
    {
        if (isset($_POST["Export"]["category_id"])) {
            $category = Categories::findOne($_POST["Export"]["category_id"]);

            ini_set('memory_limit', '12000M');
            ini_set('max_execution_time', 999);

            $list = 0;

            $PHPExcel = new \PHPExcel();

            $PHPExcel->setActiveSheetIndex($list);

            $subs = [];
            \Yii::$app->catalog->forceSubs($category->id, $subs);

            $items = Items::find()->where(["IN", "categories.id", $subs])->joinWith("categories")->orderBy("items.posled")->all();

            $PHPExcel->getActiveSheet()->setCellValue('A1', "ID");
            $PHPExcel->getActiveSheet()->setCellValue('B1', "Наименование")->getColumnDimension('B')->setWidth(50);
            $PHPExcel->getActiveSheet()->setCellValue('C1', "Изображения");
            $PHPExcel->getActiveSheet()->setCellValue('D1', "Производитель");
            $PHPExcel->getActiveSheet()->setCellValue('E1', "Цена");
            $PHPExcel->getActiveSheet()->setCellValue('F1', "Цена комплект");
            $PHPExcel->getActiveSheet()->setCellValue('G1', "Скидка");
            $PHPExcel->getActiveSheet()->setCellValue('H1', "новинка/спецпредложение/предложение дня");
            $PHPExcel->getActiveSheet()->setCellValue('I1', "Статус");
            $PHPExcel->getActiveSheet()->setCellValue('J1', "Показывать");
            $PHPExcel->getActiveSheet()->setCellValue('K1', "Характеристики")->getColumnDimension('K')->setWidth(100);
            $PHPExcel->getActiveSheet()->setCellValue('L1', "Модификации")->getColumnDimension('L')->setWidth(100);

            $PHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);

            $styleTH = new \PHPExcel_Style();
            $styleTH->applyFromArray(
                array('fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('argb' => 'FFCCFFCC')
                ),
                    'borders' => array(
                        'bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN),
                        'right' => array('style' => \PHPExcel_Style_Border::BORDER_MEDIUM)
                    )
                ));

            $PHPExcel->getActiveSheet()->setSharedStyle($styleTH, "A1:L1");

            foreach ($items as $k => $v) {
                $line = $k + 2;
                $PHPExcel->getActiveSheet()->setCellValue('A' . $line, $v->id);
                $PHPExcel->getActiveSheet()->setCellValue('B' . $line, $v->name);
                $PHPExcel->getActiveSheet()->setCellValue('C' . $line, $v->images);
                $PHPExcel->getActiveSheet()->setCellValue('D' . $line, $v->brand->name);
                $PHPExcel->getActiveSheet()->setCellValue('E' . $line, $v->price);
                $PHPExcel->getActiveSheet()->setCellValue('G' . $line, $v->percent);
                $PHPExcel->getActiveSheet()->setCellValue('H' . $line, $v->new . ";" . $v->special . ";" . $v->day_item);
                $PHPExcel->getActiveSheet()->setCellValue('I' . $line, $v->status->name);
                $PHPExcel->getActiveSheet()->setCellValue('J' . $line, $v->vis);

                $props = "";
                if ($v->props) foreach ($v->props as $kk => $vv) {
                    $props .= $vv->prop->name . "=" . $vv->value . ";";
                }

                $PHPExcel->getActiveSheet()->setCellValue('K' . $line, $props);

                $vars = "";
                if ($v->vars) foreach ($v->vars as $kk => $vv) {
                    $vars .= $vv->name . "=";
                    foreach ($vv->values as $kkk => $vvv) {
                        $varprices = ($vvv->price > 0 ? "(" . $v->price . ")" : "");
                        $vars .= $vvv->name . $varprices . "|";
                    }
                    $vars .= ";";
                }

                $PHPExcel->getActiveSheet()->setCellValue('L' . $line, $vars);
            }

            $PHPExcel->getActiveSheet()->setTitle('Экспорт товаров');
            $PHPExcel->setActiveSheetIndex($list);


            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="items-' . date("Y-m-d H:i:s") . '.xlsx"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
            $writer->save('php://output');
            exit();

        }

        return $this->render('export', []);
    }

    public function actionGetitems()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = Yii::$app->request->post();

        $success = false;
        $msg = "";
        $error = "Ошибка";

        $html = "Товаров не найдено";

        $rows = Items::find()->joinWith("categories")->where(["categories.id" => $data["category_id"]])->all();
        if($rows) {
            $success = true;
            $html = "";

            foreach ($rows as $k=>$v){
                $html .= $this->renderPartial("_item", ['item' => $v]);
            }
        }


        return ['success' => $success, 'msg' => $msg, 'error' => $error, 'html' => $html];
    }
}
