<div class="uploader-custom-item connectedSortable">
    <div class="uploader-custom-item-file">
        <? if(Yii::$app->functions->getMimeType($v["caption"]) == "image"):?>
        <img src="<?=$v["caption"];?>" alt="">
        <? else:?>
            <?=$v["file"];?>
        <? endif;?>
    </div>

    <div class="uploader-custom-item-caption">
        <input type="hidden" name="<?=$field;?>[file][]" value="<?=$v["file"];?>">
        <input class="form-control" type="text" name="<?=$field;?>[text][]" placeholder="Подпись" value="<?=$v["text"];?>">
    </div>

    <a class="btn btn-danger btn-xs uploader-custom-item-delete deleteParent" data-parent=".uploader-custom-item" href="#"><i class="glyphicon glyphicon-remove"></i></a>
</div>