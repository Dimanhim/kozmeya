<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Users extends ActiveRecord implements IdentityInterface
{
    public $disableTranslates = true;

    public $avatarUploader;
    public $profile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['username'], 'required'],
            [['password_hash', 'eauth', 'name', 'email', 'last_name', 'phone', 'country', 'index', 'city', 'address', 'country'], 'string'],
            [['status'], 'number'],
            [['username'], 'unique'],
            [['avatar'], 'safe'],
            [['avatar'], 'file', 'maxFiles' => 1000],
        ];
    }

    public function updateRelations(){}

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    public function randomPassword() {
        $alphabet = range("A", "Z").range("0", "9");
        $password = [];
        $length = strlen($alphabet) - 1;

        for ($i = 0; $i < 5; $i++) {
            $password[] = $alphabet[rand(0, $length)];
        }

        return implode($password);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function register($data, $email = true, $autologin = true){
        $success = false;
        $error = Yii::$app->langs->t("Произошла ошибка");;

        $model = new Users();

        if(Users::findOne(["username" => $data["email"]]) || Users::findOne(["email" => $data["email"]])) {
            $error = Yii::$app->langs->t("Пользователь с таким логином или e-mail уже существует");
        }
        else {
            if (isset($data["email"], $data["password"]) && $data["email"] != "" && $data["password"] != "") {
                $model->username = $data["email"];
                $model->email = $data["email"];
                if(isset($data["name"])) $model->name = $data["name"];
                if(isset($data["last_name"])) $model->last_name = $data["last_name"];
                if(isset($data["country"])) $model->country = $data["country"];

                $model->password_hash = Yii::$app->security->generatePasswordHash($data["password"]);

                if($model->save()){
                    $success = true;

                    if($email) {
                        $subject = (isset(\Yii::$app->params['settingsForms']["email_templates"]["new_user_subject"]) ? $model->regenTemplate(\Yii::$app->params['settingsForms']["email_templates"]["new_user_subject"], $data) : Yii::$app->langs->t("Добро пожаловать на сайт!").' '.Yii::$app->langs->t("Ваш логин").': '.$model->username.'<br>'.Yii::$app->langs->t("Ваш пароль").': '.$data["password"]);
                        $msg = (isset(\Yii::$app->params['settingsForms']["email_templates"]["new_user"]) ? $model->regenTemplate(\Yii::$app->params['settingsForms']["email_templates"]["new_user"], $data) : "Регистрация на сайте" . " " . Yii::$app->params['HOST']);


                        Yii::$app->mailer->compose(['html' => 'text'],['text' => $msg])
                            ->setTo($model->email)
                            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['HOST']])
                            ->setSubject($subject)
                            ->send();
                    }

                    if($autologin) {
                        Yii::$app->siteuser->login($model, (isset($data["rememberMe"]) && $data["rememberMe"] == 1 ? 3600*24*30 : 0));
                    }
                }

            }
            else {
                $error = Yii::$app->langs->t("Заполните обязательные поля");
            }
        }

        return ['success' => $success, 'error' => $error, 'model' => $model];
    }

    public function regenTemplate($msg, $data = []){
        $msg = str_replace("{id}", $this->id, $msg);
        $msg = str_replace("{username}", $this->username, $msg);
        $msg = str_replace("{name}", $this->name, $msg);
        $msg = str_replace("{password_hash}", (isset($data["password"]) ? $data["password"] : ""), $msg);

        return $msg;
    }


    public static function findByEAuth($service) {
        if (!$service->getIsAuthenticated()) {
            throw new ErrorException('EAuth user should be authenticated before creating identity.');
        }

        $attributes = $service->getAttributes();

        $eauth = $service->getServiceName().'-'.$service->getId();
        if(!$identity = Users::findOne(["eauth" => $eauth])){
            $identity = new Users();
            $identity->username = $service->getId();
            $identity->eauth = $eauth;
            $identity->password_hash = Yii::$app->security->generatePasswordHash(uniqid());
            $identity->name = $service->getAttribute('name');
            $identity->save();
        }


        return new self($identity);
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    public function columnsData(){
        $template = '{update} {delete}';

        return [
            ['class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function ($model, $key, $index, $column) {
                return ['class' => 'rowChecker', 'value' => $model->id];
            }],

            'id',
            'username',
            'email',

            ['class' => 'yii\grid\ActionColumn','template' => $template],
        ];
    }

    public function fieldsData()
    {
        return [
            'username' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'email' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'avatar' => ['type' => 'uploader', 'data' => ["name" => "avatarUploader"]],
            'password_hash' => ['type' => 'passwordInput', 'data' => ['autocomplete' => 'new-password']],
            'status' => ['type' => 'dropDownList', 'data' => [1 => "Активный"]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'email' => 'E-mail',
            'password_hash' => 'Пароль',
            'status' => 'Статус',
            'avatar' => 'Фото',
            'avatarUploader' => 'Фото',
        ];
    }

    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['user_id' => 'id'])->orderBy("id DESC");
    }
}
