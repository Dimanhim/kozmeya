
<a href="javascript:void(0);"><?=Yii::$app->langs->t("Сортировать по цене");?><span class="icon-caret"></span></a>
<div class="filter-box">
    <div class="filter-list">        
        <label class="control control-radio price-control">
            <?=Yii::$app->langs->t("По возрастанию");?>
            <input type="radio" class="reloadByValue" name="" <?=(isset($query["sort"]) && $query["sort"] == "price" ? "checked" : "");?> value="<?=Yii::$app->functions->appendToQuery(["sort" => "price"]);?>">
            <div class="control_indicator"></div>
        </label>
        <label class="control control-radio price-control">
            <?=Yii::$app->langs->t("По убыванию");?>
            <input type="radio" class="reloadByValue" name="" <?=(isset($query["sort"]) && $query["sort"] == "-price" ? "checked" : "");?> value="<?=Yii::$app->functions->appendToQuery(["sort" => "-price"]);?>">
            <div class="control_indicator"></div>
        </label>        
    </div>
</div>