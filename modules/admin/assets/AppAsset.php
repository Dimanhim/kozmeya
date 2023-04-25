<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $homeUrl = "/admin";
    public $basePath = '@webroot';
    public $baseUrl = '@web/modules/admin/';
    public $css = [
        //'/vendor/almasaeed2010/adminlte/bootstrap/css/bootstrap.min.css',
        '/vendor/almasaeed2010/adminlte/dist/css/skins/skin-blue.min.css',
		'/vendor/almasaeed2010/adminlte/dist/css/AdminLTE.min.css',
        '/vendor/almasaeed2010/adminlte/plugins/daterangepicker/daterangepicker.css',
        '/vendor/almasaeed2010/adminlte/plugins/colorpicker/bootstrap-colorpicker.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css',
        '/vendor/almasaeed2010/adminlte/plugins/datatables/dataTables.bootstrap.css',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.1/css/bootstrap-slider.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.min.css',
        'https://cdn.ckeditor.com/4.7.0/full-all/config.js?t=G14E',
        'https://cdn.ckeditor.com/4.7.0/full-all/skins/moono/editor.css?t=G14E',
        'https://cdn.ckeditor.com/4.7.0/full-all/lang/ru.js?t=G14E',
        'https://cdn.ckeditor.com/4.7.0/full-all/styles.js?t=G14E',
        'js/_system/chosen/chosen.css',
        'js/_system/alertify/css/alertify.min.css',
        'js/_system/alertify/css/themes/bootstrap.min.css',
        'css/_system/code.css',
    ];
    public $js = [
        //'/vendor/almasaeed2010/adminlte/bootstrap/js/bootstrap.min.js',
        '/vendor/almasaeed2010/adminlte/plugins/fastclick/fastclick.js',
        '/vendor/almasaeed2010/adminlte/plugins/datatables/jquery.dataTables.min.js',
        '/vendor/almasaeed2010/adminlte/plugins/datatables/dataTables.bootstrap.min.js',
        '/vendor/almasaeed2010/adminlte/plugins/input-mask/jquery.inputmask.js',
        '/vendor/almasaeed2010/adminlte/plugins/daterangepicker/moment.min.js',
        '/vendor/almasaeed2010/adminlte/plugins/daterangepicker/daterangepicker.js',
        '/vendor/almasaeed2010/adminlte/plugins/colorpicker/bootstrap-colorpicker.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.1/bootstrap-slider.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.3/select2_locale_ru.min.js',
        '/vendor/almasaeed2010/adminlte/plugins/jQueryUI/jquery-ui.min.js',
        'https://cdn.ckeditor.com/4.7.0/full-all/ckeditor.js',
		'/vendor/almasaeed2010/adminlte/dist/js/app.min.js',
        'js/_system/jQuery-File-Upload/js/vendor/jquery.ui.widget.js',
        'js/_system/jQuery-File-Upload/js/jquery.iframe-transport.js',
        'js/_system/jQuery-File-Upload/js/jquery.fileupload.js',
        'js/_system/chosen/chosen.jquery.js',
        'js/_system/alertify/alertify.min.js',
        'js/_system/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
