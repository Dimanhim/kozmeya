<a href="<?=\Yii::$app->catalog->categoryUrl($v->id);?>">
    <span><?=count($v->items);?> <?=\Yii::$app->functions->plural(count($v->items), "предложение", "предложения", "предложений");?></span>
    <img src="<?=\Yii::$app->functions->getUploadItem($v, "images", "ra", "280x245");?>" alt="<?=$v->name;?>" width="280" height="245">
    <?=$v->name;?>
</a>
