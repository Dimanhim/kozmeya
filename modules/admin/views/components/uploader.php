<?
use kartik\file\FileInput;

$files = \Yii::$app->functions->getUploadItemsNames($model->{$field});
$previews = [];
$previewsConf = [];
foreach($files as $index=>$file){
    $previews[] = "/sitefiles/".\Yii::$app->functions->getModelName($model)."/".$file;
    $previewsConf[$index] = array(
        "caption" => "/sitefiles/".\Yii::$app->functions->getModelName($model)."/".$file,
        "url" => "/admin/ajax/deletefile",
        "key" => Yii::$app->functions->getModelName($model)."::".$model->id."::".$file."::".$field,
    );
}

echo $form->field($model, $name.'[]')->widget(FileInput::classname(), [
    'options' => ['multiple' => true],
    'pluginOptions' => [
        'overwriteInitial' => false,
        'initialPreview'=> $previews,
        'initialPreviewAsData'=>true,
        'initialPreviewConfig' => $previewsConf,

        'previewFileType' => 'any',
        'showRemove' => true,
    ]
]);
?>