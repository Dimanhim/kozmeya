<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [

    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];

    private static function getCss() {
        return [
            'css/normalize.css?ver=222',
            'css/skeleton.css?ver=222',
            'css/jquery.mCustomScrollbar.min.css?ver=222',
            'css/icons.css?ver=222',
            'css/fonts.css?ver=222',
            'css/sprites.css?ver=222',
            'css/bootstrap.css?ver=222',
            'css/slick.css?ver=222',
            'css/slick-theme.css?ver=222',
            'css/swiper.css?ver=222',
            'css/jquery.fancybox.min.css?ver=222',
            'css/project.css?ver='.mt_rand(1000,10000),
            'css/media.css?ver='.mt_rand(1000,10000),
            'js/_system/alertify/css/alertify.min.css?ver=222',
            'js/_system/alertify/css/themes/bootstrap.min.css?ver=222',
            'css/_system/code.css?ver=222',
            'css/invert.css?ver='.mt_rand(1000,10000),
        ];
    }

    private static function getJs() {
        return [
            'js/bootstrap.min.js',
            'js/slick.min.js',
            'js/swiper.min.js',
            'js/jquery.fancybox.min.js',
            'js/lib.js',
            'js/jquery.mCustomScrollbar.concat.min.js',
            'js/jquery.validate.min.js',
            'js/project.js?v='.mt_rand(1000,10000),

            'js/_system/base64.js',
            'js/_system/blockUI.js',
            'js/_system/alertify/alertify.min.js',
            'js/_system/jQuery-File-Upload/js/vendor/jquery.ui.widget.js',
            'js/_system/jQuery-File-Upload/js/jquery.fileupload.js',
            'js/_system/jQuery-File-Upload/js/jquery.iframe-transport.js',

            'js/_system/app.js?ver=222'.mt_rand(1000,10000),
        ];
    }

    public function init()
    {
        $this->css = static::getCss();
        $this->js = static::getJs();
    }
}
