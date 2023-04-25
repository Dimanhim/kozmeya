<? if(isset(Yii::$app->params['menuCatsPid'][0])): $categoriesSale = \app\models\Categories::find()->joinWith("items")->where("items.special = 1 AND categories.parent != 0 AND categories.vis = 1 AND categories.menu = 1")->orderBy("categories.posled")->all();?>
    <ul class="d-md-flex justify-content-center">
        <? foreach(Yii::$app->params['menuCatsPid'][0] as $k=>$v):?>
        <li class="<? if(isset(Yii::$app->params['menuCatsPid'][$v->id]) || ($v->id == 37 && $categoriesSale)):?>have-submenu<? endif;?> <? if(isset(\Yii::$app->params['currentCategory']->id) && \Yii::$app->params['currentCategory']->id == $v->id):?>active<? endif;?>">
            <a
                <? if(isset(Yii::$app->params['menuCatsPid'][$v->id]) || ($v->id == 37 && $categoriesSale)):?>
                    href="<?=\Yii::$app->catalog->categoryUrl($v->id);?>"
                <? else:?>
                    href="<?=\Yii::$app->catalog->categoryUrl($v->id);?>"
                <? endif;?>
            >
	            
                <?=Yii::$app->langs->modelt($v, 'name');?>
                <? if(isset(Yii::$app->params['menuCatsPid'][$v->id]) || ($v->id == 37 && $categoriesSale)):?><span class="caret"></span><? endif;?>
            </a>

            <? if($v->id == 37):?>
                <? if($categoriesSale):?>                    
                    <div class="submenu">
                    
                        <? if($main):?>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-2 offset-md-2">
                                    <div class="left_menu-content">
                                    <?// endif;?>
                                        <ul>                                    
                                            <? foreach ($categoriesSale as $kk=>$vv):?>
                                                <li><a href="<?=\Yii::$app->catalog->categoryUrl($v->id);?>?filters[categories][<?=$vv->id;?>]=<?=$vv->id;?>"><?=($vv->anchor != "" ? Yii::$app->langs->modelt($vv, 'anchor') : Yii::$app->langs->modelt($vv, 'name'));?></a></li>
                                            <? endforeach;?>
                                        </ul>

                                    <?// if($main):?>
                                    </div>
                                </div>
                                <? if($v->menuimages != ""):?>
                                    <div class="col-md-6">
                                        <div class="menu_banner">
                                            <img src="<?=\Yii::$app->functions->getUploadItem($v, "menuimages", "rn", "460x199");?>">
                                        </div>
                                    </div>
                                <? endif;?>
                            </div>
                        </div>
                        <? endif;?>
                    </div>
                <? endif;?>
            <? else:?>

                <? if(isset(Yii::$app->params['menuCatsPid'][$v->id])):?>
                    <div class="submenu">
                        <? if($main):?>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-2 offset-md-2">
                                    <div class="left_menu-content">
                                    <? endif;?>

                                    <ul>
                                        <? foreach (Yii::$app->params['menuCatsPid'][$v->id] as $kk=>$vv):?>
                                            <li><a href="<?=\Yii::$app->catalog->categoryUrl($vv->id);?>"><?=Yii::$app->langs->modelt($vv, 'name');?></a></li>
                                        <? endforeach;?>
                                    </ul>

                                    <? if($main):?>
                                    </div>
                                </div>
                                <? if($v->menuimages != ""):?>
                                    <div class="col-md-6">
                                        <div class="menu_banner">
                                            <img src="<?=\Yii::$app->functions->getUploadItem($v, "menuimages", "rn", "460x199");?>">
                                        </div>
                                    </div>
                                <? endif;?>
                            </div>
                        </div>
                    <? endif;?>
                    </div>
                <? endif;?>
            <? endif;?>
        </li>
        <? endforeach;?>
    </ul>
<? endif;?>