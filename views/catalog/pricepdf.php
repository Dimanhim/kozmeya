<?
use yii\helpers\Html;
?>

<table>
    <thead>
        <tr>
            <th><?=Yii::$app->langs->t("Изображение")?></th>
            <th><?=Yii::$app->langs->t("Наименование")?></th>
            <th><?=Yii::$app->langs->t("Стоимость")?></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($items as $k=>$v):?>
        <tr>
            <td><img src="<?=\Yii::$app->params["HOST"];?><?=trim(\Yii::$app->functions->getUploadItem($v, "images", "fx", "70x70"), "/");?>" alt="<?=$v->name;?>"></td>
            <td><?=$v->name;?></td>
            <td><?=\Yii::$app->catalog->viewPrice($v, "price");?></td>
        </tr>
        <? endforeach;?>
    </tbody>
</table>
