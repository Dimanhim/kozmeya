<? if($slider):?>
    <div class="container-fluid">
        <div class="main_slider slide_bar">
            <? foreach ($slider as $k=>$v):?>
                <div class="slide">
                    <img src="<?=\Yii::$app->functions->getUploadItem($v, "images");?>" class="w-100">
                    <span class="slider_block">
                        <b><?=$v->name;?></b>
                        <? if($v->link != ""):?>
                            <a href="<?=$v->link;?>"><?=$v->btn;?></a>
                        <? endif;?>
                    </span>
                </div>
            <? endforeach;?>
        </div>
    </div>
<? endif;?>

<div class="container">
    <? if(isset(Yii::$app->params['mainCats']) && count(Yii::$app->params['mainCats']) > 0) foreach(Yii::$app->params['mainCats'] as $k=>$v):?>

    <? if ($k == 0): ?>
        <div class="main_model">
    		<div class="row">
    			<div class="col-lg-3">
    				<span class="main_banner-title"><a href="<?=\Yii::$app->catalog->categoryUrl($v->id);?>"><?=$v->name;?></a></span>
    				<p class="main_banner-text">
    					<?=$v->small;?>
    				</p>
                    <a class="main_banner-btn" href="<?=\Yii::$app->catalog->categoryUrl($v->id);?>">Style Request</a>
    			</div>
    			<div class="col-lg-9">
<!--                     <a href="<?//=\Yii::$app->catalog->categoryUrl($v->id);?>"> -->
                        <img class="w-100" src="<?=\Yii::$app->functions->getUploadItem($v, "images");?>">
<!--                         <span class="banner_text">Смотреть</span> -->
<!--                     </a> -->
    			</div>
    		</div>
        </div>
    <? else: ?>
        <?php
            $btnName = Yii::$app->langs->t("Смотреть все");
            if($v->name == 'SEMI-BESPOKE') {
                $btnName = 'COLLECTION';
            }
            elseif($v->name == 'converted') {
                $btnName = 'VISIT';
            }
            elseif($v->name == 'occasion') {
                $btnName = 'VISIT';
            }
            ?>
	    <div class="main_banner-<?=($k%2==0 ? 'right' : 'left');?>">
	        <div class="row">

		        <? if ( $k%2==0 ): ?>

    		        <div class="col-lg-6">
                        <div class="main_banner-img">
<!--                             <a href="<?//=\Yii::$app->catalog->categoryUrl($v->id);?>"> -->
                                <img class="w-100" src="<?=\Yii::$app->functions->getUploadItem($v, "images");?>">
<!--                                 <span class="banner_text">Смотреть</span> -->
<!--                             </a> -->
                        </div>
                    </div>

    	            <div class="col-lg-3">
    	                <span class="main_banner-title"><a href="javascript:void(0);"><?=$v->name;?></a></span>
    	                <p class="main_banner-text"><?=$v->small;?></p>
    	                <a class="main_banner-btn" href="<?=\Yii::$app->catalog->categoryUrl($v->id);?>"><?=$btnName;?></a>
    	            </div>

		        <? else: ?>
		            <div class="col-lg-6">
                        <div class="main_banner-img">
<!--                             <a href="<?=\Yii::$app->catalog->categoryUrl($v->id);?>"> -->
                                <img class="w-100" src="<?=\Yii::$app->functions->getUploadItem($v, "images");?>">
           <!--                      <span class="banner_text">Смотреть</span> -->
                           <!--  </a> -->
                        </div>
                    </div>
    	            <div class="col-lg-3">
    	                <span class="main_banner-title"><a href="javascript:void(0);"><?=$v->name;?></a></span>
    	                <p class="main_banner-text"><?=$v->small;?></p>
    	                <a class="main_banner-btn" href="<?=\Yii::$app->catalog->categoryUrl($v->id);?>"><?=$btnName;?></a>
    	            </div>
                <? endif; ?>
	        </div>
	    </div>
    <? endif; ?>

    <? if($v->items):?>
    <div class="models_list">
        <div class="product_slider">
            <? foreach ($v->items as $kk=>$vv):?>
                <?php if($vv->vis) : ?>
                    <div class="product_slider-item">
                        <?= $this->render( '/catalog/parts/item', ['v' => $vv, 'catalog' => true] ); ?>
                    </div>
                <?php endif; ?>
            <? endforeach;?>
        </div>
    </div>
    <? endif;?>

    <? endforeach;?>
</div>
