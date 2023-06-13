<div class="dashboard-admin">
    <div class="dashboard-title"><a href="/admin"><?=Yii::$app->langs->t("Панель управления сайтом")?></a></div>
    <hr>
    <? if(\Yii::$app->params['editLink'] != ""):?><a class="dashboard-edit-link" href="/admin/<?=\Yii::$app->params['editLink'];?>"><?=Yii::$app->langs->t("Редактировать")?></a><? endif;?>
</div>
