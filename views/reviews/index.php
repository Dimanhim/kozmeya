<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\Paginator;
use yii\widgets\Pjax;
?>

<?
$ranks = \Yii::$app->db->createCommand("SELECT COUNT(*) as count,
                                            SUM(rating_1) as rating_1,
                                            SUM(rating_2) as rating_2,
                                            SUM(rating_3) as rating_3
                                            FROM reviews WHERE vis = '1'")->queryOne();

$avg = round(($ranks['rating_1']+$ranks['rating_2']+$ranks['rating_3'])/$ranks['count']/3);
$rating_1 = round(($ranks['rating_1'])/$ranks['count']);
$rating_2 = round(($ranks['rating_2'])/$ranks['count']);
$rating_3 = round(($ranks['rating_3'])/$ranks['count']);
?>

<main class="work__area">
<div class="container">
<header class="work__area-head">
<?= $this->render( '/parts/bcrumbs', [] ); ?>
<h1 class="h1-head"><?=Html::encode(\Yii::$app->meta->getPageTitle($page->name));?></h1>
<div class="assessments-area row">
    <div class="col-md-4 col-sm-6">
        <div class="assessments-area-elem">
            <div class="assessments-area-elem-title">Оценки пользователей</div>
            <div class="rev-name-r">
                <div data-score="<?=$avg;?>" class="rev-raity"></div>
            </div>
            <div class="average_rating">

                Средня оценка: <span><?=$avg;?></span>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="assessments-area-elem">
            <div class="assessments-area-elem-title">Подробные оценки</div>
            <ul class="list assessments-list">
                <li class="clearfix">
                    <div class="assessments-list-title">Качество товаров:</div>
                    <div class="assessments-square">
                        <div class="square__area">
                            <div data-score="<?=$rating_1;?>" class="square-raty"></div>
                        </div><span class="square-rait-num"><?=$rating_1;?></span>
                    </div>
                </li>
                <li class="clearfix">
                    <div class="assessments-list-title">Качество общения:</div>
                    <div class="assessments-square">
                        <div class="square__area">
                            <div data-score="<?=$rating_2;?>" class="square-raty"></div>
                        </div><span class="square-rait-num"><?=$rating_2;?></span>
                    </div>
                </li>
                <li class="clearfix">
                    <div class="assessments-list-title">Качество доставки:</div>
                    <div class="assessments-square">
                        <div class="square__area">
                            <div data-score="<?=$rating_3;?>" class="square-raty"></div>
                        </div><span class="square-rait-num"><?=$rating_3;?></span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="write__feedback free__serv rev-page">
    <h4>Оставить отзыв</h4>
    <form class="addReview">
        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

        <div class="clearfix">
            <div class="write__feedback-col-3">
                <div class="form-box"><span class="float-text">Общая оценка*</span>
                    <div class="white__rait">
                        <div data-score="0" data-name="raty_1" class="feedback-raty"></div>
                    </div>
                </div>
                <div class="form-box"><span class="float-text">Качество товаров</span>
                    <div class="white__rait">
                        <div data-score="0" data-name="raty_2" class="feedback-raty"></div>
                    </div>
                </div>
                <div class="form-box"><span class="float-text">Качество общения</span>
                    <div class="white__rait">
                        <div data-score="0" data-name="raty_3" class="feedback-raty"></div>
                    </div>
                </div>
                <div class="form-box"><span class="float-text">Качество доставки</span>
                    <div class="white__rait">
                        <div data-score="0" data-name="raty_4" class="feedback-raty"></div>
                    </div>
                </div>
            </div>
            <div class="write__feedback-col-4 fleft">
                <div class="clearfix">
                    <div class="write__feedback-col-1">
                        <div class="form-box">
                            <input type="text" placeholder="Имя" name="name" class="required">
                        </div>
                    </div>
                    <div class="write__feedback-col-2">
                        <div class="form-box"><span class="float-text">E-mail</span>
                            <input type="email" placeholder="name@info.ru" name="email" class="required">
                        </div>
                    </div>
                </div>
                <div class="form-box">
                    <textarea placeholder="Сообщение" name="text" class="required"></textarea>
                </div>
                <div class="form-box">
                    <button type="submit" class="send__dtn"><span class="red__btn red__btn-arrow">отправить         </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<br>
<br>
<div class="card__tabs-content">
    <div class="tab-all-rev">
        <h4>Все отзывы</h4>

        <?php Pjax::begin([
            'id' => 'reviewsGrid',
            'enablePushState' => true,
        ]); ?>

        <ul class="list tab-all-rev-list over">
            <? foreach($dataProvider->getModels() as $k=>$v):?>
                <?= $this->render('/reviews/parts/item', ['v' => $v, 'video' => $video] ); ?>
            <? endforeach;?>
        </ul>

        <div class="clearfix">
            <div class="pagination fright">
            <?=Paginator::widget([
                'pagination' => $dataProvider->getPagination(),
                'activePageCssClass' => 'current',
                'nextPageCssClass' => 'next',
                'prevPageCssClass' => 'prev',
                'nextPageLabel' => '&nbsp;',
                'prevPageLabel' => '&nbsp;'
            ]);?>
            </div>
        </div>

        <?php Pjax::end(); ?>
    </div>
</div>
<br>
<br>
<br>
</header>
</div>
</main>