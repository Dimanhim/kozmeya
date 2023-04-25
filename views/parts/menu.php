<? if(isset(Yii::$app->params['topPagesPid'][0])):?>
    <nav class="header-top-nav <?=$class;?>">
        <ul class="list clearfix">
            <? foreach(Yii::$app->params['topPagesPid'][0] as $k=>$v):?>
            <li class="<? if(isset(Yii::$app->params['currentPage']->id) && $v->id == Yii::$app->params['currentPage']->id):?>active<? endif;?>">
                <a href="<?=\Yii::$app->functions->hierarchyUrl($v);?>"><?=Yii::$app->langs->modelt($v, 'name');?></a>
            </li>
            <? endforeach;?>
        </ul>
    </nav>
<? endif;?>

