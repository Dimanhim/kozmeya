<h1>
    <?=$_SERVER["HTTP_HOST"];?>
</h1>

<section class="content">
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <? $count = Yii::$app->db->createCommand("SELECT COUNT(id) as count FROM orders WHERE CAST(`date` AS DATE) = '".date("Y-m-d")."'")->queryScalar();?>
                <h3><?=$count;?></h3>

                <p><?=\Yii::$app->functions->plural($count, "Заказ за сегодня", "Заказа за сегодня", "Заказов за сегодня");?></p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/admin/orders/index" class="small-box-footer">Смотреть <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <? $count = Yii::$app->db->createCommand("SELECT COUNT(id) as count FROM items")->queryScalar();?>
                <h3><?=$count;?></h3>

                <p><?=\Yii::$app->functions->plural($count, "Товар", "Товара", "Товаров");?></p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="/admin/items/create" class="small-box-footer">Добавить <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->

    <? /*
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <? $count = Yii::$app->db->createCommand("SELECT COUNT(id) as count FROM users")->queryScalar();?>
                <h3><?=$count;?></h3>

                <p><?=\Yii::$app->functions->plural($count, "Пользователь", "Пользователя", "Пользователей");?></p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="/admin/users" class="small-box-footer">Смотреть <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    */ ?>
</div>
<!-- /.row -->

<div class="row">
    <div class="col-lg-6 col-xs-6">
        <div class="box box-info">
            <div class="box-header">
                <i class="fa fa-dashboard"></i>

                <h3 class="box-title">Импорт SEO</h3>

            </div>
            <div class="box-body">
                <?= $this->render('/seo/_import', []) ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-xs-6">
        <div class="box box-info">
            <div class="box-header">
                <i class="fa fa-share"></i>

                <h3 class="box-title">Импорт Редиректов</h3>
            </div>
            <div class="box-body">
                <?= $this->render('/redirects/_import', []) ?>
            </div>
        </div>
    </div>
</div>
</section>