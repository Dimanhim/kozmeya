<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\SettingsForms;
use Yii;
use app\models\Emailer;
use yii\console\Controller;


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class EmailerController extends \app\components\Console
{
    public function actionIndex()
    {
        $settings = SettingsForms::findOne(["code" => "mail"]);

        if($settings) {
            $settingsData = Json::decode($settings->form_data);
            $settingsData["class"] = 'Swift_SmtpTransport';
            Yii::$app->mailer->setTransport($settingsData);
        }


        $emailers = Emailer::find()->where("status = '1' AND emails != ''")->all();

        foreach ($emailers as $k=>$v)
        {
            $v->status = 2;
            $v->save();

            Yii::$app->mailer->compose(['html' => 'text'], ['text' => $v->text])
                    ->setTo(array_filter(explode(",", trim($v->emails))))
                    ->setFrom([$this->params['supportEmail'] => $this->params['HOST']])
                    ->setSubject($v->subject)
                    ->send();

            $v->status = 3;
            $v->save();

        }
    }
}
