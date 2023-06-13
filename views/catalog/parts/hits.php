<? if(!isset($best)) $best = \app\models\Items::find()->where("vis = '1' AND best = '1'")->orderBy("posled")->all();?>
<? if($best):?>
    <div class="container hits__obj">
        <div class="tag-elem"><?=Yii::$app->langs->t("самые популярные решения")?></div>
        <h3 class="h-3">
            <?=Yii::$app->langs->t("Хиты продаж")?>
        </h3>
        <div class="product_hit-carousel owl-carousel">
            <? foreach($best as $k=>$v):?>
                <div class="product_hit-elem-out">
                    <?= $this->render('/catalog/parts/item', ['v' => $v, 'catalog' => true] ); ?>
                </div>
            <? endforeach;?>
        </div>

        <a href="/<?=\Yii::$app->params['allPages'][\Yii::$app->params['catalogPageId']]->alias;?>" class="red__btn red__btn-arrow pos-t-r"><?=Yii::$app->langs->t("смотреть все")?></a>
    </div>
<? endif;?>
