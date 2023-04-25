<div class="breadcrumbs">
    <ul class="list breadcrumbs-list">
        <? foreach(\Yii::$app->params['bcrumbs'] as $k=>$v):?>
            <? if($k != count(\Yii::$app->params['bcrumbs'])-1):?>
                <li><a href="<?=$v["link"];?>"><?=$v["name"];?></a></li>
            <? else:?>
                <li><span><?=$v["name"];?></span></li>
            <? endif;?>
        <? endforeach;?>
    </ul>
</div>
