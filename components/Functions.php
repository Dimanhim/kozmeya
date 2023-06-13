<?
namespace app\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;
use yii\helpers\Json;
use linslin\yii2\curl;

class Functions  extends Component
{
    public function init(){
        parent::init();
    }

    public function getModelName($model){
        $getClassData = explode("\\", get_class($model));

        $name = (is_array($getClassData) ? end($getClassData) : $getClassData);

        return $name;
    }

    public function getPrice( $value )
    {
        return number_format($value, 0, ',', ' ');
    }

    public function onlyNumbers($value){
        $value = preg_replace('/\D/', '', $value);
        return $value;
    }

    public function getMimeType($filename)
    {
        $mimetype = false;
        if(function_exists('finfo_fopen')) {
            // open with FileInfo
            return "file";
        } elseif(function_exists('getimagesize')) {
            // open with GD
            return "image";
        } elseif(function_exists('exif_imagetype')) {
            // open with EXIF
        } elseif(function_exists('mime_content_type')) {
            $mimetype = mime_content_type($filename);
        }
        return $mimetype;
    }

    public function forceSubs($id, &$ids)
    {
        if(isset(\Yii::$app->params['allPagesPid'][$id]))
        {
            foreach(\Yii::$app->params['allPagesPid'][$id] as $k=>$v)
            {
                if($v->vis)
                {
                    $ids[$v->id] = $v->id;
                }

                $this->forceSubs($v->id, $ids);
            }
        }

        $ids[$id] = $id;
    }

    public function forceParent($page, &$parent)
    {
        if($page->parent == 0) {
            $parent = $page;
        }
        else {
            $this->forceParent(\Yii::$app->params['allPages'][$page->parent], $parent);
        }
    }

    public function hierarchy($data, &$datas, $object = 'allPages')
    {
        if(isset(\Yii::$app->params[$object][$data->id])) $datas[$data->id] = \Yii::$app->params[$object][$data->id];

        if(isset(\Yii::$app->params[$object][$data->parent]) && $data->id != $data->parent)
        {
            $this->hierarchy(\Yii::$app->params[$object][$data->parent], $datas, $object);
        }
    }

    public function hierarchyUrl($data, $object = 'allPages') {
        $datas = [];

        $this->hierarchy($data, $datas, $object);

        $datas = array_reverse($datas);

        $url = "";
        foreach ($datas as $k=>$v) {
            $url .= "/".$v->alias;
        }


        return $url;
    }

    public function getUploadItem($model, $field = "images", $method = "", $size = ""){
        $items = explode(';', $model->{$field});
        $item = ($items ? array_shift($items) : '');
        $item = explode("::", $item);

        return "/".Yii::$app->params['uploadDir'].($method ? "/".$method : "").($size ? "/".$size : "")."/".$this->getModelName($model)."/".$item[0];
    }

    public function getUploadItemNext($model, $field = "images", $method = "", $size = ""){

        $items = explode(';', $model->{$field});
        $item = '';

        if ($items)
        {
	        $i = 0;
	        foreach ($items as $k=>$v)
	        {
		        if ( $v != '' )
		        {
			        $i++;
			        if ( $i > 1 )
			        {
				       $item = $v;
				       break;
			        }
		        }
	        }
        }

       // $item = ($items ? array_shift($items) : '');
        $item = explode("::", $item);

        return "/".Yii::$app->params['uploadDir'].($method ? "/".$method : "").($size ? "/".$size : "")."/".$this->getModelName($model)."/".$item[0];
    }

    public function getLang( $lang = '' )
    {
	    if ( $lang == 'ru' )
	    {
		    return 'Россия (RU)';
	    }
	    else
	    {
		    return 'English (EN)';
	    }
    }


    public function getUploadItemText($model, $field = "images", $method = "", $size = ""){
        $items = explode(';', $model->{$field});
        $item = ($items ? array_shift($items) : '');
        $item = explode("::", $item);

        return ["file" => "/".Yii::$app->params['uploadDir'].($method ? "/".$method : "").($size ? "/".$size : "")."/".$this->getModelName($model)."/".$item[0], "text" => (isset($item[1]) ? $item[1] : "")];
    }

    public function getUploadItems($model, $field = "images"){
        $items = explode(";", $model->{$field});
        $items = array_filter($items);
        $itemsArray = [];

        foreach($items as $item){
            $item = explode("::", $item);

            $itemsArray[] = "/".Yii::$app->params['uploadDir']."/{options}/".$this->getModelName($model)."/".$item[0];
        }

        return $itemsArray;
    }

    public function getUploadItemsText($model, $field = "images"){
        $items = explode(";", $model->{$field});
        $items = array_filter($items);
        $itemsArray = [];

        foreach($items as $index=>$item){
            $item = explode("::", $item);

            $itemsArray[$index]["file"] = "/".Yii::$app->params['uploadDir']."/{options}/".$this->getModelName($model)."/".$item[0];
            $itemsArray[$index]["text"] = (isset($item[1]) ? $item[1] : "");
        }

        return $itemsArray;
    }

    public function getUploadItemsNames($items){
        $items = explode(";", $items);
        $items = array_filter($items);

        $result = [];
        foreach($items as $item){
            $item = explode("::", $item);

            $result[] = $item[0];
        }

        return $result;
    }

    public function getUploadItemsNamesText($items){
        $items = explode(";", $items);
        $items = array_filter($items);

        $result = [];
        foreach($items as $index=>$item){
            $item = explode("::", $item);

            $result[$index]["file"] = $item[0];
            $result[$index]["text"] = (isset($item[1]) ? $item[1] : "");
        }

        return $result;
    }

    public function setPhoto($photo, $method = "", $size = ""){
        return str_replace("{options}", ($method != "" ? $method.($size != "" ? "/" : "") : "").$size, $photo);
    }
    public function setPhotoMain($photo){
        $symb = ["{options}", "/Items"];
        $replaced = ['', 'Items'];
        return str_replace($symb, $replaced, $photo);
    }

    public function uploaderSimple(&$model, $field, $fileField = "images", $index = 0, $folder = ""){

        if(isset($_FILES[$field]["tmp_name"][$fileField][$index]) && $_FILES[$field]["tmp_name"][$fileField][$index] != ""){
            if($folder == "") {
                $folder = $this->getModelName($model);
            }

            if (!is_dir(\Yii::$app->basePath.'/'.Yii::$app->params['uploadDir'].'/'.$folder))
            {
                @mkdir(\Yii::$app->basePath.'/'.Yii::$app->params['uploadDir'].'/'.$folder, 0777);
            }

            while (true) {
                $file = $_FILES[$field]["name"][$fileField][$index];
                $file = pathinfo($file);

                $filename = \Yii::$app->security->generateRandomString() . '.' . $file['extension'];
                if (!file_exists(\Yii::$app->basePath."/".Yii::$app->params['uploadDir']."/".$folder."/".$filename)) break;
            }

            if(move_uploaded_file($_FILES[$field]["tmp_name"][$fileField][$index], \Yii::$app->basePath.'/'.Yii::$app->params['uploadDir'].'/'.$folder."/".$filename)){
                $model->{$fileField} = $filename.";";
                return $model->{$fileField};
            }
        }
        else {
            return false;
        }
    }

    public function modelUploads(&$model){
        if(method_exists($model, "fieldsData")) {
            $fields = $model->fieldsData();
            foreach ($fields as $field => $fieldData) {
                if($fieldData["type"] == "uploader_custom") {
                    $this->fileappender($model, $field);
                }
                elseif($fieldData["type"] == "uploader") {
                    \Yii::$app->functions->uploader($model, $field, (isset($fieldData["data"]["name"]) ? $fieldData["data"]["name"] : ""));
                }
            }
        }
    }
    public function uploader(&$model, $field, $var = "imagesUploader", $folder = ""){


        if($folder == "") {
            $folder = $this->getModelName($model);
        }

        if (!is_dir(\Yii::$app->basePath.'/'.Yii::$app->params['uploadDir'].'/'.$folder))
        {
            @mkdir(\Yii::$app->basePath.'/'.Yii::$app->params['uploadDir'].'/'.$folder, 0777);
        }

        $model->{$var} = UploadedFile::getInstances($model, $var);

        $images = "";
        if ($model->{$var} && $model->validate()) {
            foreach ($model->{$var} as $file) {
                while (true) {
                    $filename = \Yii::$app->security->generateRandomString() . '.' . $file->extension;
                    if (!file_exists(\Yii::$app->basePath."/".Yii::$app->params['uploadDir']."/".$folder."/".$filename)) break;
                }

                $file->saveAs(Yii::$app->params['uploadDir'].'/'.$folder.'/' . $filename);
                $images .= $filename.";";
            }
        }


        $model->{$field} .= $images;
    }

    public function fileappender(&$model, $field){
        $data = Yii::$app->request->post();

        $model->{$field} = "";
        if(isset($data[$field]["file"]) && is_array($data[$field]["file"]) && count($data[$field]["file"]) > 0) {
            foreach($data[$field]["file"] as $index=>$file){
                $model->{$field} .= $file;
                if(isset($data[$field]["text"][$index]) && $data[$field]["text"][$index] != "") {
                    $model->{$field} .= "::".$data[$field]["text"][$index];
                }
                $model->{$field} .= ";";
            }
        }
    }

    public function searcher($s){
        $s = preg_replace('/\s\s+/', ' ', trim($s));
        $sFormatter = $this->latToCyr($s);

        $models = [
            'News' => ['fields' => ['name'], 'slug' => 'alias', 'name' => 'name', 'image' => 'images', 'url' => "/".\Yii::$app->params['allPages'][10]->alias],
            'StaticPage' => ['fields' => ['name'], 'name' => 'name', 'slug' => 'alias', 'url' => ''],
        ];

        $sections = [];

        foreach ($models as $model => $data) {
            $model = Yii::createObject([
                    'class' => "app\models\\".$model,
            ]);

            $finder = $model::find()->where("vis = '1'");

            foreach ($data["fields"] as $field) {
                $finder->andWhere("(`".$field."` LIKE ".Yii::$app->db->quoteValue("%".$s."%")." OR `".$field."` LIKE ".Yii::$app->db->quoteValue("%".$sFormatter."%").")");
            }

            $posts = $finder->all();

            foreach($posts as $k=>$v){
                $sections[] = ["image" => (isset($data["image"]) && $data["image"] != "" ? $this->getUploadItem($v, $data["image"], "fx", "50x50") : ""), "name" => $v->{$data["name"]}, "url" => $data["url"]."/".$v->{$data["slug"]}];
            }
        }


        return $sections;
    }

    public function setViewer($model){
        $cookies = Yii::$app->response->cookies;

        if(!$cookies->has("view_".\Yii::$app->functions->getModelName($model)."_".$model->id)){
            $model->count_view++;
            $model->save();

            $cookies->add(new \yii\web\Cookie([
                'name' => "view_".\Yii::$app->functions->getModelName($model)."_".$model->id,
                'value' => 1,
            ]));
        }
    }

    public function validateUrl($url){
        if($url != \Yii::$app->params['nonGetUrl']){
            throw new \yii\web\NotFoundHttpException;
        }
    }

    public function getAlphabet($data, $letterfield = "name")
    {
        $alphabet = array();
        foreach ($data as $k => $v) {
            $uppercase = mb_strtoupper(mb_substr($v->{$letterfield}, 0, 1, 'utf-8'), 'utf-8').mb_substr($v->{$letterfield}, 1, mb_strlen($v->{$letterfield}, 'utf-8'), 'utf-8');
            $letter = mb_substr($uppercase,0,1,'utf-8');

            if(preg_match('/[А-Яа-яЁё]/u', $letter)) {
                $alphabet['ru'][$letter][] = $v;
            }
            else {
                $alphabet['en'][$letter][] = $v;
            }
        }

        if(isset($alphabet['en'])) ksort($alphabet['en']);
        if(isset($alphabet['ru'])) ksort($alphabet['ru']);

        return $alphabet;
    }

    public function viewRating($value, $tag = "span", $active = "active", $disabled = "")
    {
        $rating = "";

        for ( $i = 1; $i <= 5; $i++ )
        {
            if ( $value >= $i )
            {
                $rating .= '<'.$tag.' class="'.$active.'"></'.$tag.'>';
            }
            else
            {
                $rating .= '<'.$tag.' class="'.$disabled.'"></'.$tag.'>';
            }
        }
        return $rating;
    }

    public function add2navi($name, $link = ""){
        \Yii::$app->params['bcrumbs'][] = ["link" => ($link != "" ? "/".trim($link, "/") : ""), "name" => $name];
    }

    public function add2navihierarchy($data, $append = "", $object = 'allPages'){
        $datas = [];

        $this->hierarchy($data, $datas, $object);

        $datas = array_reverse($datas);


        foreach ($datas as $k=>$v) {
            $this->add2navi($v->name, $append.$this->hierarchyUrl($v, $object));
        }
    }

    public function shortcodes($text){ // example ({shortcode_parts_contact_form|params1=paramvalue1,param2=paramvalue2})
        if(preg_match('/\{shortcode_(.+?)_(.+?)\}/', $text, $match)){
            if($match[1] == "parts") {
                $data = [];
                if(preg_match('/\|(.+?)$/', $match[2], $datas)) {
                    $match[2] = str_replace($datas[0], "", $match[2]);

                    $datas = explode(",", $datas[1]);
                    foreach ($datas as $dataParams){
                        $dataParam = explode("=", $dataParams);
                        $data[$dataParam[0]] = $dataParam[1];
                    }
                }

                $html = Yii::$app->controller->renderPartial("/parts/".$match[2], $data);
                $text = str_replace($match[0], $html, $text);
            }

        }

        return $text;
    }

    public function simpleExport($rows){
        ini_set('memory_limit', '12000M');
        ini_set('max_execution_time', 999);

        if($rows) {
            $fields = $rows[0]->attributeLabels();

            $list = 0;

            $PHPExcel = new \PHPExcel();
            $PHPExcel->setActiveSheetIndex($list);

            $letter = "A";
            foreach ($fields as $k=>$v){
                if(!preg_match("/Uploader/", $k)) {
                    $PHPExcel->getActiveSheet()->setCellValue($letter.'1', $v);
                    $letter++;
                }
            }

            $line = 1;foreach($rows as $k=>$v){
                $line++;
                $letter = "A";
                foreach ($fields as $kk=>$vv){
                    if(!preg_match("/Uploader/", $kk)) {
                        $PHPExcel->getActiveSheet()->setCellValue($letter . $line, $v->{$kk});
                        $letter++;
                    }
                }
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

            return true;
        }
        else {
            return false;
        }
    }

    public function plural($n, $form1, $form2, $form5)
    {
        $n = abs($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) return $form5;
        if ($n1 > 1 && $n1 < 5) return $form2;
        if ($n1 == 1) return $form1;
        return $form5;
    }

    public function urlExists($url) {
        $file_headers = @get_headers($url);
        if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $exists = false;
        }
        else {
            $exists = true;
        }

        return $exists;
    }

    public function setAlias($value)
    {
        $data = [
                'а' => 'a',
                'б' => 'b',
                'в' => 'v',
                'г' => 'g',
                'д' => 'd',
                'е' => 'e',
                'ё' => 'yo',
                'ж' => 'zh',
                'з' => 'z',
                'и' => 'i',
                'й' => 'j',
                'к' => 'k',
                'л' => 'l',
                'м' => 'm',
                'н' => 'n',
                'о' => 'o',
                'п' => 'p',
                'р' => 'r',
                'с' => 's',
                'т' => 't',
                'у' => 'u',
                'ф' => 'f',
                'х' => 'h',
                'ц' => 'c',
                'ч' => 'ch',
                'ш' => 'sh',
                'щ' => 'shh',
                'ъ' => '',
                'ы' => 'y',
                'ь' => '',
                'э' => 'e',
                'ю' => 'yu',
                'я' => 'ya',
                '@' => '-',
                ' ' => '-',
                '\\' => '-',
                '/' => '-',
                '|' => '-',
                '=' => '-',
                '+' => '-',
                '&' => '-',
                ',' => '-',
                '"' => '-',
                '\'' => '-',
                '(' => '-',
                ')' => '-',
                '?' => '',
                '[' => '-',
                ']' => '-',
                '#' => '-',
                '№' => '-',
                '!' => '',
                '`' => '-',
                '{' => '-',
                '}' => '-',
                '<' => '-',
                '>' => '-',
                '%' => '-',
                '~' => '-',
                "'" => '-',
                '^' => '-'];

        // replace non letter or digits by -
        $slug = preg_replace('~[^\pL\d]+~u', '-', $value);

        $slug = mb_strtolower($slug, 'UTF-8');
        $slug = str_replace(array_keys($data), $data, $slug);

        $slug = trim($slug, "_-");
        $slug = preg_replace("/([-_])([_-]*)/", "-", $slug);

        // remove unwanted characters
        $slug = preg_replace('~[^-\w]+~', '', $slug);

        // trim
        $slug = trim($slug, '-');

        // remove duplicate -
        $slug = preg_replace('~-+~', '-', $slug);

        // lowercase
        $slug = strtolower($slug);

        if (empty($slug)) {
            $slug = 'n-a';
        }

        return $slug;
    }


    public function saveCustomFields($model){
        $data = Yii::$app->request->post();

        $customfields = [];

        if(isset($data["CustomFields"]) && is_array($data["CustomFields"]) && count($data["CustomFields"]) > 0) {
            foreach($data["CustomFields"] as $field => $value){
                if($value != "" && $field != "") {
                    $customfields[$field] = $value;
                }
            }
        }

        if(isset($model->_customfields)) {
            \Yii::$app->db->createCommand()->update($model->tableName(), ['_customfields' => Json::encode($customfields)], 'id = '.$model->id)->execute();
        }
    }

    public function getCF($model, $field){
        if(isset($model->_customfields) && $model->_customfields != ""){
            $values = Json::decode($model->_customfields, false);
            if(isset($values->{$field})) {
                return $values->{$field};
            }
        }

        return "";
    }

    public function latToCyr($query){
        $array = array(
            "q" => "й", "w" => "ц", "e" => "у", "r" => "к", "t" => "е", "y" => "н", "u" => "г", "i" => "ш", "o" => "щ", "p" => "з",
            "[" => "х", "]" => "ъ", "a" => "ф", "s" => "ы", "d" => "в", "f" => "а", "g" => "п", "h" => "р", "j" => "о", "k" => "л",
            "l" => "д", ";" => "ж", "'" => "э", "z" => "я", "x" => "ч", "c" => "с", "v" => "м", "b" => "и", "n" => "т", "m" => "ь",
            "," => "б", "." => "ю"
        );

        mb_internal_encoding("UTF-8");

        foreach($array as $k=>$v){
            $array[ucfirst($k)] = mb_strtoupper(mb_substr($v, 0, 1)) . mb_substr($v, 1);
        }

        return str_replace(array_keys($array), array_values($array), $query);
    }

    public function curl($url, $data, $method = "get"){
        $curl = new curl\Curl();

        $response = ($method == "get" ? $curl->setGetParams($data) : $curl->setPostParams($data));
        $response->setOption(CURLOPT_FAILONERROR, 0)->setOption(CURLOPT_RETURNTRANSFER, 1)->setOption(CURLOPT_TIMEOUT, 3);

        if($method == "get") {
            $response->get($url);
        }
        else {
            $response->post($url);
        }

        if ($curl->errorCode === null) {
            switch ($curl->responseCode) {

                case 'timeout':
                    //timeout error logic here
                    break;

                case 200:
                    //success logic here
                    return (isset($response->response) ? $response->response : "");
                    break;

                case 404:
                    //404 Error logic here
                    break;
            }
        } else {
            // List of curl error codes here https://curl.haxx.se/libcurl/c/libcurl-errors.html
            switch ($curl->errorCode) {

                case 6:
                    //host unknown example
                    break;
            }
        }

        return false;
    }

    public function fileInfo($file){
        $size = filesize($file);
        $info = pathinfo($file);

        return $info + ["size" => $this->humanFileSize($size)];
    }

    public function humanFileSize($bytes)
    {
        $result = "";

        $bytes = floatval($bytes);
        $bytesData = [
                0 => ["unit" => "TB", "value" => pow(1024, 4)],
                1 => ["unit" => "GB", "value" => pow(1024, 3)],
                2 => ["unit" => "MB", "value" => pow(1024, 2)],
                3 => ["unit" => "KB", "value" => 1024],
                4 => ["unit" => "B",  "value" => 1],
        ];

        foreach($bytesData as $byteData)
        {
            if($bytes >= $byteData["value"])
            {
                $result = $bytes / $byteData["value"];
                $result = str_replace(".", "," , strval(round($result, 2)))." ".$byteData["unit"];
                break;
            }
        }

        return $result;
    }

    public function appendToQuery($attr = []){
        $get = Yii::$app->request->get();
        unset($get["alias"]);
        unset($get["_pjax"]);
        unset($get[key($attr)]);

        $get = $get + $attr;

        $getParams = (count($get) > 0 ? "?".http_build_query($get) : "");

        return $getParams;
    }

    public function removeToQuery($attrs = []){
        $get = Yii::$app->request->get();
        unset($get["alias"]);
        unset($get["_pjax"]);
        foreach ($attrs as $key => $attr) {
            unset($get[$key]);
        }

        $getParams = (count($get) > 0 ? "?".http_build_query($get) : "");

        return $getParams;
    }
}
