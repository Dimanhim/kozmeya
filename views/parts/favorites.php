<span class="auth_title"><i class="heart_icon"></i>&nbsp;&nbsp;<?=Yii::$app->langs->t("Избранное");?></span>


<? if(count(\Yii::$app->params['favorites']) > 0):?>
    <div class="models_list">
        <div class="row">
            <? $index = 0; foreach (\Yii::$app->params['favorites'] as $k=>$v): $index++; if($index <= 4):?>
                <div class="col-6 col-xl-3 col-md-4">
                    <?= $this->render( '/catalog/parts/item', ['v' => $v, 'catalog' => true] ); ?>
                </div>
            <? endif; endforeach;?>
        </div>
        <a href="/favorites" class="goto"><?=Yii::$app->langs->t("Посмотрите все товары в избранном")?>: <?=count(\Yii::$app->params['favorites']);?></a>
    </div>
<? else:?>
    <div class="favorites_block-text">
        <?=Yii::$app->langs->t("Нажмите");?> <i class="heart_icon"></i> <?=Yii::$app->langs->t("рядом с товаром для добавления его в Избранное.<br>Так будет проще найти понравившийся вам товар позже и оформить покупку.");?>
    </div>
<? endif;?>
