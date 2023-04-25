<?
namespace app\modules\admin\components;

use app\models\Categories;
use app\models\Settings;
use app\models\SettingsForms;
use app\models\StaticPage;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

class AdminController extends \yii\web\Controller
{
    public $layout = '@app/modules/admin/views/layouts/main';

    public $title = "";
    public $permissions = ["full" => [], "view" => []];
    public $permissionSections = [];
    public $filtersData = '';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            return true;
        } else {
            return false;
        }
    }

    public function init(){
        \Yii::$app->errorHandler->errorAction = 'admin/index/error';

		$this->initSession();
		$this->initSettings();
        $this->initPermissions();
        $this->initTranslates();
        $this->initStaticPage();
        $this->initCatalog();

        $this->enableCsrfValidation = false;

        //\Yii::configure($this, require(__DIR__ . '/../config/admin.php'));

        parent::init();
    }

    public function initCatalog(){
        \Yii::$app->params['catalogPageId'] = 2;

        $categories = Categories::find()->orderBy("posled")->all();
        \Yii::$app->params['categories']  = $categories;


        foreach($categories as $k=>$v){
            if($v->vis == 1) {
                \Yii::$app->params['allCategories'][$v->id] = $v;
                \Yii::$app->params['allCategoriesPid'][$v->parent][$v->id] = $v;


                if($v->menu == 1) {
                    \Yii::$app->params['menuCats'][]  = $v;
                    \Yii::$app->params['menuCatsPid'][$v->parent][]  = $v;
                }

                if($v->main == 1) {
                    \Yii::$app->params['mainCats'][]  = $v;
                    \Yii::$app->params['mainCatsPid'][$v->parent][]  = $v;
                }
            }
        }
    }


    public function initStaticPage(){
        $pages = StaticPage::find()->orderBy("posled")->all();
        \Yii::$app->params['pages']  = $pages;

        foreach($pages as $k=>$v){
            if($v->vis == 1) {
                \Yii::$app->params['allPages'][$v->id] = $v;
                \Yii::$app->params['allPagesPid'][$v->parent][$v->id] = $v;
            }
            if($v->top == 1) {
                \Yii::$app->params['topPages'][$v->id] = $v;
                \Yii::$app->params['topPagesPid'][$v->parent][$v->id] = $v;
            }
            if($v->bottom == 1) {
                \Yii::$app->params['bottomPages'][$v->id] = $v;
                \Yii::$app->params['bottomPagesPid'][$v->parent][$v->id] = $v;
            }
            if($v->left == 1) {
                \Yii::$app->params['leftPages'][$v->id] = $v;
                \Yii::$app->params['leftPagesPid'][$v->parent][$v->id] = $v;
            }
            if($v->main == 1) {
                \Yii::$app->params['mainPages'][$v->id] = $v;
                \Yii::$app->params['mainPagesPid'][$v->parent][$v->id] = $v;
            }
        }
    }


    public function initSettings()
    {
        $rows = Settings::find()->all();

        foreach($rows as $k=>$v){
            \Yii::$app->params['settings'][$v->id] = $v->text;
        }

        $rows = SettingsForms::find()->all();
        foreach($rows as $k=>$v){
            \Yii::$app->params['settingsForms'][$v->code] = Json::decode($v->form_data);
        }


        if(isset(\Yii::$app->params['settingsForms']["mail"]["smtp"]) && \Yii::$app->params['settingsForms']["mail"]["smtp"] == 1){
            $smtpConfig = \Yii::$app->params['settingsForms']["mail"];
            unset($smtpConfig["smtp"]);
            $smtpConfig["class"] = 'Swift_SmtpTransport';
            Yii::$app->mailer->setTransport($smtpConfig);
        }
    }


	public function initSession(){
		\Yii::$app->session->open();
	}

    public function initPermissions(){
        $this->permissionSections = $this->allcontrollers();
        \Yii::$app->params["permissionSections"] = $this->permissionSections;

        if(!Yii::$app->user->isGuest && Yii::$app->user->identity->permissions) {
            \Yii::$app->db->createCommand("UPDATE `adminusers` SET `last_online`= '".date("Y-m-d H:i:s")."' WHERE id = '".Yii::$app->user->identity->getId()."';")->execute();

            foreach(Yii::$app->user->identity->permissions as $k=>$v){
                $this->permissions["view"][$v->section] = true;

                if($v->access == "full") {
                    $this->permissions["full"][$v->section] = true;
                }
            }

            \Yii::$app->params["permissions"] = $this->permissions;

            $url = trim(str_replace("/admin", "", strtok(\Yii::$app->request->url, "?")), "/");

            if(!Yii::$app->user->identity->root && !in_array($this->id, ["index", "ajax", "error"]) && !isset($this->permissions["view"][$this->id])) {
                throw new \yii\web\ForbiddenHttpException;
            }

            if($this->id != "index" && !Yii::$app->user->identity->root && $url != "" && !in_array($url, ["login", "logout", "ajax", "error"]) && !preg_match("/ajax/", $url) && !preg_match("/index/", $url) && !preg_match("/error/", $url) && !preg_match("/^ajax/", $url) && $url != $this->id && !isset($this->permissions["full"][$this->id])) {
                throw new \yii\web\ForbiddenHttpException;
            }
        }
    }

    public function initTranslates(){
        \Yii::$app->langs->setup();
    }

    protected function allcontrollers()
    {
        $controllerlist = [];

        $path = __DIR__.'/../controllers';
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                    $filecontent = file_get_contents($path."/".$file);
                    if(preg_match('/public \$status.*?=.*?"(.*?)";/', $filecontent, $status)) {
                        if($status[1] == "active") {
                            if (preg_match('/\$this->view->title.*?=.*?"(.*?)";/', $filecontent, $find)) {
                                $controllerlist[str_replace("controller.php", "", strtolower($file))] = $find[1];
                            } else {
                                $controllerlist[str_replace("controller.php", "", strtolower($file))] = str_replace("controller.php", "", strtolower($file));
                            }
                        }
                    }
                }
            }
            closedir($handle);
        }
        asort($controllerlist);

        return $controllerlist;
    }

    public function allmodels()
    {
        $modelslist = [];

        if ($handle = opendir(__DIR__.'/../../../models')) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && preg_match("/\.php/", $file)) {
                    $model = str_replace(".php", "", $file);
                    $modelslist[$model] = $model;
                }
            }
            closedir($handle);
        }
        asort($modelslist);

        return $modelslist;
    }
}