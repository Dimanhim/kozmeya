<div class="<?=$class;?>">
    <article class="news-elem">
        <div class="row">
            <div class="col-md-12 col-sm-6">
                <div class="news-elem-date"><?=date("d.m.Y", strtotime($v->date));?></div>
                <div class="news-elem-pic">
                    <a href="<?=$v->url;?>">
                        <img src="<?=\Yii::$app->functions->getUploadItem($v, "images", "fx", "360x220");?>" alt="<?=$v->name;?>">
                    </a>
                </div>
            </div>
            <div class="col-md-12 col-sm-6">
                <h4 class="news-elem-title"> <a href="<?=$v->url;?>"><?=$v->name;?></a></h4>
                <p><?=$v->small;?></p>
            </div>
        </div>
    </article>
</div>