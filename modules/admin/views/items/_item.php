<?
use yii\helpers\Html;
?>

<div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><a data-pjax="1" data-confirm="Вы уверены?" href="/admin/items/delete?id=<?=$item->id;?>">×</a></button>
    <h4><a href="/admin/items/update?id=<?=$item->id;?>"><i class="icon fa fa-pencil"></i></a>  <?=$item->name;?></h4>
</div>