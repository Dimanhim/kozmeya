<?
use yii\helpers\Url;
$root = Yii::$app->user->identity->root;
?>
<!-- sidebar menu: : style can be found in sidebar.less -->
<ul class="sidebar-menu no-print">
    <li class="header">Разделы</li>
    <?  foreach($menu as $k=>$v): $access_route = trim(str_replace("/admin/", "", $v["route"]), "/");?>
        <? if($root || $access_route == "" || isset(\Yii::$app->params["permissions"]["view"][$access_route])):?>
            <? if(isset($v["tree"]) && count($v["tree"]) > 0): $active = false; foreach($v["tree"] as $kk=>$vv) { if((Yii::$app->request->url == $vv['route'])) $active = true;}?>
                <li class="treeview <? if($active):?>active<? endif;?>">
                    <a href="<?=Url::toRoute($v["route"]);?>">
                        <i class="<?=$v["icon"];?>"></i> <span><?=$v["name"];?></span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>

                    <ul class="treeview-menu">
                        <? foreach($v["tree"] as $kk=>$vv): $access_route = trim(str_replace("/admin/", "", $vv["route"]), "/");?>
                            <? if($root || $access_route == "" || isset(\Yii::$app->params["permissions"]["view"][$access_route])):?>
		                        <li class="<?=(Yii::$app->request->url == $vv['route'] ? 'active' : '')?>"><a href="<?=Url::toRoute($vv["route"]);?>"><i class="<?=$vv["icon"];?>"></i> <?=$vv["name"];?></a></li>
                            <? endif;?>
                        <? endforeach;?>
                    </ul>
                </li>
            <? else:?>
                <li class="<? if(preg_match("/".Yii::$app->controller->id."/", $v["route"])):?>active<? endif;?>"><a href="<?=Url::toRoute($v["route"]);?>"><i class="<?=$v["icon"];?>"></i> <span><?=$v["name"];?></span></a></li>
            <? endif;?>
        <? endif;?>
    <? endforeach;?>
</ul>