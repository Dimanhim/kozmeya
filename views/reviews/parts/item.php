<li class="clearfix">
    <div class="rev-name">
        <div class="rev-name-r">
            <div data-score="<?=$v->rating;?>" class="rev-raity"></div>
        </div><?=$v->name;?><em class="rev-name-date"><?=date("d.m.Y", strtotime($v->date));?></em>
    </div>
    <div class="rev-list-info">
        <ul class="list rev-list-info-list clearfix">
            <li class="clearfix">
                <div class="assessments-list-title"><?=\Yii::$app->langs->t("Качество товаров")?>:</div>
                <div class="assessments-square">
                    <div class="square__area">
                        <div data-score="<?=$v->rating_1;?>" class="square-raty"></div>
                    </div><span class="square-rait-num"><?=$v->rating_1;?></span>
                </div>
            </li>
            <li class="clearfix">
                <div class="assessments-list-title"><?=\Yii::$app->langs->t("Качество общения")?>:</div>
                <div class="assessments-square">
                    <div class="square__area">
                        <div data-score="<?=$v->rating_2;?>" class="square-raty"></div>
                    </div><span class="square-rait-num"><?=$v->rating_2;?></span>
                </div>
            </li>
            <li class="clearfix">
                <div class="assessments-list-title"><?=\Yii::$app->langs->t("Качество доставки")?>:</div>
                <div class="assessments-square">
                    <div class="square__area">
                        <div data-score="<?=$v->rating_3;?>" class="square-raty"></div>
                    </div><span class="square-rait-num"><?=$v->rating_3;?></span>
                </div>
            </li>
        </ul>
        <p><?=$v->text;?></p>
    </div>
</li>
