<?
use kartik\file\FileInput;

$files = \Yii::$app->functions->getUploadItemsNamesText($model->{$field});
$previews = [];

foreach($files as $index=>$file){
    $previews[$index] = array(
        "file" => $file["file"],
        "caption" => "/sitefiles/".$methodSize.\Yii::$app->functions->getModelName($model)."/".$file["file"],
        "text" => $file["text"],
    );
}

$labels = $model->attributeLabels();
?>

<? if(isset($labels[$field])):?><label class="control-label"><?=$labels[$field];?></label><? endif;?>

<div class="uploader-custom-items-block">
    <div class="uploader-custom-items sortable">
        <? foreach($previews as $k=>$v):?>
            <?= $this->render( '/components/uploader_custom/parts/item', ['field' => $field, 'v' => $v] ); ?>
        <? endforeach;?>
    </div>

    <div class="uploader-custom-items-uploader">
        <a class="btn btn-default btn-lg fileuploadClick">Добавить файл</a>
        <input data-sequential-uploads="true" data-form-data='{"methodSize" : "<?=$methodSize;?>", "field": "<?=$field;?>", "folder": "<?=Yii::$app->functions->getModelName($model);?>"}' type="file" name="filesLoader[]" class="fileupload hide" multiple>
    </div>
</div>

<hr>