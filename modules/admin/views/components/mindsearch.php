<?
use yii\helpers\Json;

$labels = $model->attributeLabels();
?>

<div class="form-group mindSearchBlock">
    <label class="control-label"><?=$labels[$field];?></label>

    <input model="<?=$modelClass;?>" fields='<?=Json::encode($fields);?>' searchfields='<?=Json::encode($searchfields);?>' placeholder="Начать поиск" class="mindselect" style="width: 100%;">
    <div style="margin-top: 10px; font-weight: bold;" class="mindSearchResult">
        <? if($model->{$field} != 0):?>
        <?=(isset($model->{$field."0"}) && $model->{$field."0"} ? $model->{$field."0"}->name : $model->{$field});?> <a class="btn btn-danger removeMindSearchValue" href="#"><i class="fa fa-trash"></i></a>
        <? endif;?>
    </div>

    <?= $form->field($model, $field)->hiddenInput(['class' => 'hiddenMindSearch'])->label(false) ?>
</div>
