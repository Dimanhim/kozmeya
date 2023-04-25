<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\Paginator;
use yii\widgets\Pjax;
?>

<main class="work__area">
    <div class="container">
        <header class="work__area-head">
            <?= $this->render( '/parts/bcrumbs', [] ); ?>

            <h1 class="h1-head"><?=Html::encode(\Yii::$app->meta->getPageTitle($page->name));?></h1>
        </header>
        <div class="row work-in-company">
            <div class="col-md-7">
                <?=$page->text;?>
            </div>
            <div class="col-md-5">
                <aside>
                    <div class="open__vacanies">
                        <h3 class="h-3">Открытые вакансии</h3>
                        <ul class="list open__vacanies-list">
                            <? foreach($items as $k=>$v):?>
                            <li><a href="<?=$v->url;?>"><?=$v->name;?></a></li>
                            <? endforeach;?>
                        </ul>

                        <small>Не нашли подходящей вакансии? <br>Присылайте свои предложения о сотрудничестве на <a href="mailto:<?=Yii::$app->params["settings"][8];?>"><?=Yii::$app->params["settings"][8];?></a></small>
                    </div>
                </aside>
            </div>
        </div>
    </div>
    <div class="map-outer">
        <div id="map"></div>
        <div class="question__box-out">
            <div class="question__box">
                <?= $this->render( '/parts/contact_form', [] ); ?>
            </div>
        </div>
    </div>
</main>