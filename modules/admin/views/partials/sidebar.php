<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar no-print">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <? if(!Yii::$app->user->isGuest):?>
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?=(Yii::$app->user->identity->avatar != "" ? \Yii::$app->functions->getUploadItem(Yii::$app->user->identity, "avatar", "ra", "160x160") : "http://placehold.it/160x160");?>" class="img-circle" alt="<?=Yii::$app->user->identity->username;?>">
            </div>
            <div class="pull-left info">
                <p><?=Yii::$app->user->identity->username;?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> В сети</a>
            </div>
        </div>
        <? endif;?>

        <!-- search form -->
        <? /*
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Поиск...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        */ ?>
        <!-- /.search form -->

        <?
        $settings = \Yii::$app->db->createCommand("SELECT `id`, `name` FROM `settings_forms` WHERE id IN (2,3)")->queryAll();

        $settingsMenu = [['name' => 'Основные', 'route' => '/admin/settings', 'icon' => 'fa fa-fw fa-cogs']];
        if($settings) foreach($settings as $k=>$v){
            $settingsMenu[] = ['name' => $v["name"], 'route' => '/admin/settingsforms/update?id='.$v["id"], 'icon' => 'fa fa-fw fa-check-circle'];
        }
        $settingsMenu[] = ['name' => 'Подписка', 'route' => '/admin/subscribes', 'icon' => 'fa fa-fw fa-envelope'];
        //$settingsMenu[] = ['name' => 'Рассылка', 'route' => '/admin/emailer', 'icon' => 'fa fa-fw fa-envelope'];

        ?>

        <? $menu = [
            ['name' => 'Настройки сайта', 'route' => '', 'icon' => 'fa fa-cogs', 'tree' => $settingsMenu],


            ['name' => 'Пользователи системы', 'route' => '/admin/adminusers', 'icon' => 'fa fa-fw fa-user'],
            ['name' => 'Пользователи сайта', 'route' => '/admin/users', 'icon' => 'fa fa-fw fa-user'],

            ['name' => 'Стат. страницы', 'route' => '/admin/static', 'icon' => 'fa fa-files-o'],
            ['name' => 'Слайдер', 'route' => '/admin/slider', 'icon' => 'fa fa-photo'],
            //['name' => 'Мини-галереи', 'route' => '/admin/sliders', 'icon' => 'fa fa-photo'],

            ['name' => 'Каталог', 'route' => '', 'icon' => 'fa fa-pie-chart', 'tree' => [
                ['name' => 'Категории', 'route' => '/admin/categories', 'icon' => 'fa fa-fw fa-folder-open'],
                ['name' => 'Товары', 'route' => '/admin/items', 'icon' => 'fa fa-fw fa-cart-plus'],
                ['name' => 'Коллекции', 'route' => '/admin/brands', 'icon' => 'fa fa-puzzle-piece'],
               // ['name' => 'Страны', 'route' => '/admin/countries', 'icon' => 'fa fa-plane'],
                ['name' => 'Характеристики', 'route' => '/admin/props', 'icon' => 'fa fa-filter'],
                ['name' => 'Цвета', 'route' => '/admin/colors', 'icon' => 'fa fa-check-circle'],
                ['name' => 'Размеры', 'route' => '/admin/sizes', 'icon' => 'fa fa-check-circle'],
                ['name' => 'Группы размеров', 'route' => '/admin/sizesgroups', 'icon' => 'fa fa-check-circle'],

                //['name' => 'Значения модификаций', 'route' => '/admin/varsshowtypesvalues', 'icon' => 'fa fa-filter'],
                //['name' => 'Теги', 'route' => '/admin/tags', 'icon' => 'fa fa-tags'],
                //['name' => 'Фильтры', 'route' => '/admin/filters', 'icon' => 'fa fa-filter'],
                //['name' => 'Отзывы о товарах', 'route' => '/admin/itemsreviews', 'icon' => 'fa fa-commenting'],
                ['name' => 'Валюта', 'route' => '/admin/currency', 'icon' => 'fa fa-credit-card'],
                ['name' => 'Статусы', 'route' => '/admin/itemsstatus', 'icon' => 'fa fa-check-circle'],
                //['name' => 'Импорт', 'route' => '/admin/items/import', 'icon' => 'fa fa-file-excel-o'],
                //['name' => 'Экспорт', 'route' => '/admin/items/export', 'icon' => 'fa fa-file-excel-o'],
            ]],

            ['name' => 'Заказы', 'route' => '', 'icon' => 'fa fa-cart-plus', 'tree' => [
                ['name' => 'Заказы', 'route' => '/admin/orders', 'icon' => 'fa fa-cart-plus'],
                //['name' => 'Доставка', 'route' => '/admin/orders/deliveries', 'icon' => 'fa fa-truck'],
                ['name' => 'Пункты самовывоза', 'route' => '/admin/pickuppoints', 'icon' => 'fa fa-thumb-tack'],
                ['name' => 'Типы доставки', 'route' => '/admin/deliveries', 'icon' => 'fa fa-truck'],
                ['name' => 'Методы оплаты', 'route' => '/admin/payments', 'icon' => 'fa fa-credit-card'],
                ['name' => 'Промо-коды', 'route' => '/admin/promocodes', 'icon' => 'fa fa-barcode'],
                ['name' => 'Статусы', 'route' => '/admin/ordersstatuses', 'icon' => 'fa fa-check-circle'],
            ]],

            ['name' => 'Страны', 'route' => '/admin/deliverycountries', 'icon' => 'fa fa-check-circle'],
            ['name' => 'Города', 'route' => '/admin/deliverycities', 'icon' => 'fa fa-check-circle'],

            ['name' => 'Лиды', 'route' => '/admin/leads', 'icon' => 'fa fa-fw fa-phone'],
            //['name' => 'Отзывы', 'route' => '/admin/reviews', 'icon' => 'fa fa-fw fa-commenting'],
            //['name' => 'Лица компании', 'route' => '/admin/faces', 'icon' => 'fa fa-fw fa-child'],
            //['name' => 'Партнеры', 'route' => '/admin/partners', 'icon' => 'fa fa-fw fa-meh-o'],
            //['name' => 'FAQ', 'route' => '/admin/faq', 'icon' => 'fa fa-fw fa-question-circle'],
            //['name' => 'Акции', 'route' => '/admin/actions', 'icon' => 'fa fa-calendar'],
            //['name' => 'Новости', 'route' => '/admin/news', 'icon' => 'fa fa-calendar'],
            //['name' => 'Комментарии', 'route' => '/admin/comments', 'icon' => 'fa fa-comment-o'],
            /*
            ['name' => 'Вакансии', 'route' => '', 'icon' => 'fa fa-clock-o', 'tree' => [
                ['name' => 'Категории', 'route' => '/admin/vacanciescategories', 'icon' => 'fa fa-check-circle'],
                ['name' => 'Вакансии', 'route' => '/admin/vacancies', 'icon' => 'fa fa-check-circle'],
            ]],
            */
            ['name' => 'Локализация', 'route' => '', 'icon' => 'fa fa-language', 'tree' => [
                ['name' => 'Языки', 'route' => '/admin/langs', 'icon' => 'fa fa-check-circle'],
                ['name' => 'Переводы', 'route' => '/admin/translates', 'icon' => 'fa fa-check-circle'],
            ]],

            ['name' => 'SEO', 'route' => '', 'icon' => 'fa fa-dashboard', 'tree' => [
                ['name' => 'SEO', 'route' => '/admin/seo', 'icon' => 'fa fa-dashboard'],
                ['name' => 'Редиректы', 'route' => '/admin/redirects', 'icon' => 'fa fa-share'],
                ['name' => 'Системные файлы', 'route' => '/admin/systemfiles', 'icon' => 'fa fa-file'],
            ]],
        ];?>

        <?= $this->render( '/partials/menu', ['menu' => $menu] ); ?>
    </section>
    <!-- /.sidebar -->
</aside>
