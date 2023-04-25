<?
use yii\helpers\Html;
?>


<div class="header_top">
    <div class="container">
        <div class="page-title d-flex align-items-center">
            <div><?=Yii::$app->langs->t("Избранные товары");?></div>
        </div>
    </div>
</div><!--.header_top-->

<div class="container">    
   
   <? if(Yii::$app->siteuser->isGuest):?>
        <span class="my_title text3"><a href="/profile/register"><?=Yii::$app->langs->t("Войдите или создайте учетную запись");?></a></span>
    <? endif;?>

    <? if(count(\Yii::$app->params['favorites']) > 0):?>            
            <div class="row">
                <? foreach (\Yii::$app->params['favorites'] as $k=>$v):?>
                    <div class="col-lg-3 col-md-6">
                        <?= $this->render( '/catalog/parts/item', ['v' => $v, 'catalog' => true, 'favorites' => true] ); ?>
                    </div>
                <? endforeach;?>
            </div>        
        <? else:?>
        <div class="lk_wrap">
            <div class="lk_block-title">
                <?=Yii::$app->langs->t("У Вас еще нет любимых товаров");?><br><br>
                <small><?=Yii::$app->langs->t("Зайдите в наш магазин, нажмите иконку");?> <i class="heart_icon"></i> <?=Yii::$app->langs->t("на странице товара, чтобы добавить его в этот список");?></small>
            </div>
            <a href="/" class="lk_btn"><?=Yii::$app->langs->t("В магазин");?></a>
        </div>
    <? endif;?>

</div>

