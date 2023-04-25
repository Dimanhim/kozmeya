<?php

namespace app\components;

use app\models\StaticPage;
use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {

        $pages = StaticPage::find()->where(["!=", "_router", ""])->andWhere(["vis" => 1])->all();

        $routes = [];

        foreach ($pages as $k => $v) {
            $router = array_filter(explode(";", $v->_router));

            foreach ($router as $route) {
                $routeData = explode("=", $route);

                if($v->parent != 0) {
                    $routeData[0] = str_replace("/<slug>", "<pre>/<slug>", $routeData[0]);
                }

                $routes[str_replace("<slug>", $v->alias, $routeData[0])] = $routeData[1];
            }
        }

        $routes['/ajax/<action>'] = 'ajax/<action>';
        //$routes['/<controller>/<action>'] = '<controller>/<action>';
        $routes['/<alias:(.+)>'] = 'site/static';



        $app->getUrlManager()->addRules($routes);

    }
}