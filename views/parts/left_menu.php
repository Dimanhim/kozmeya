<div class="p-nav__top clearfix visible-xs visible-sm">
    <span class="p-nav__currnet-page"><?=$page->name;?></span>
    <button class="p-nav__toggle-btn  js-p-nav-toggle" type="button"><i></i></button>
</div>

<? if(isset(Yii::$app->params['allPagesPid'][($page->parent == 0 ? $page->id : $page->parent)])):?>
<div class="p-nav">
    <ul class="p-nav__list">
        <? foreach(Yii::$app->params['allPagesPid'][($page->parent == 0 ? $page->id : $page->parent)] as $k=>$v):?>
        <li class="p-nav__item <? if($page->id == $v->id):?>hidden-xs hidden-sm current<? endif;?>">
            <a href="<?=\Yii::$app->functions->hierarchyUrl($v);?>"><?=$v->name;?></a>
        </li>
        <? endforeach;?>
    </ul>
</div>
<? endif;?>