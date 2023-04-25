<footer class="main-footer no-print">
    <div class="pull-right hidden-xs">
        <b>Версия</b> 2.0
    </div>
    <strong>Copyright &copy; 2014-<?=date("Y");?> <a href="http://vikiweb.ru">Vikiweb</a></strong>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark no-print">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
        <li class="active"><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane" id="control-sidebar-home-tab">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Написать в тех.поддержку</h3>
                </div>
                <div class="box-body">
                    <form class="form-ajax">
                        <input type="hidden" name="Форма" value="Ошибка на сайте <?=Yii::$app->params["HOST"];?>">
                        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                        <input type="hidden" name="_toadmin" value="1" />

                        <div class="form-group">
                            <input name="Заголовок" class="form-control input-sm required" type="text" placeholder="Заголовок">
                        </div>
                        <div class="form-group">
                            <textarea name="Сообщение" class="form-control input-sm required" placeholder="Сообщение"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Отправить</button>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.tab-pane -->

        <!-- Settings tab content -->
        <div class="tab-pane active" id="control-sidebar-settings-tab">
            <div>
                <h4 class="control-sidebar-heading">Пользователи онлайн</h4>
                <? $onlines = Yii::$app->db->createCommand("SELECT * FROM `adminusers` WHERE `last_online` > '".date("Y-m-d H:i:s", strtotime("-10 minutes"))."'")->queryAll();?>
                <? if($onlines):?>
                    <? foreach($onlines as $online):?>
                        <div><strong><?=$online["username"];?></strong></div>
                    <? endforeach;?>
                <? endif;?>
                <hr>
            </div>

            <div>
                <h4 class="control-sidebar-heading">Карта сайта</h4>
                <a href="#" class="btn btn-success generateSitemap">Сгенерировать карту сайта</a>
                <a href="/sitemap.xml" target="_blank">Открыть карту сайта</a>
                <hr>
            </div>
        </div>
        <!-- /.tab-pane -->
    </div>
</aside>
<!-- /.control-sidebar -->