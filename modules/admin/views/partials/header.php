<header class="main-header no-print">

<!-- Logo -->
<a href="/" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>+</b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><?=$_SERVER["HTTP_HOST"];?></span>
</a>

<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
<!-- Sidebar toggle button-->
<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
    <span class="sr-only">Toggle navigation</span>
</a>
<!-- Navbar Right Menu -->
<div class="navbar-custom-menu">
<ul class="nav navbar-nav">
<? if(!Yii::$app->user->isGuest):?>
<!-- User Account: style can be found in dropdown.less -->
<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="<?=(Yii::$app->user->identity->avatar != "" ? \Yii::$app->functions->getUploadItem(Yii::$app->user->identity, "avatar", "ra", "160x160") : "http://placehold.it/160x160");?>" class="user-image" alt="<?=Yii::$app->user->identity->username;?>">
        <span class="hidden-xs"><?=Yii::$app->user->identity->username;?></span>
    </a>
    <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">
            <img src="<?=(Yii::$app->user->identity->avatar != "" ? \Yii::$app->functions->getUploadItem(Yii::$app->user->identity, "avatar", "ra", "160x160") : "http://placehold.it/160x160");?>" class="img-circle" alt="<?=Yii::$app->user->identity->username;?>">

            <p>
                <?=Yii::$app->user->identity->username;?>
                <small><?=\Yii::$app->formatter->asDate(Yii::$app->user->identity->created_at, "long");?></small>
            </p>
        </li>
        <!-- Menu Body -->
        <li class="user-body">
            <div class="row">
                <div class="col-xs-4 text-center">
                    <a href="#"></a>
                </div>

            </div>
            <!-- /.row -->
        </li>
        <!-- Menu Footer-->
        <li class="user-footer">
            <div class="pull-right">
                <a href="/admin/logout" class="btn btn-default btn-flat">Выйти</a>
            </div>
        </li>
    </ul>
</li>
<? endif;?>

<!-- Control Sidebar Toggle Button -->
<li>
    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
</li>
</ul>
</div>

</nav>
</header>