<div><a href="" class="checkAll" data-selector=".recursive_checkboxes_<?=str_replace(["[", "]"], "_", $inputname);?>">Отметить все</a></div>
<?


\Yii::$app->adminfunctions->recursiveCheckboxes($data, 0,  $field, $inputname, $checked );
?>