<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "permissons".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $section
 * @property string $access
 *
 * @property Adminusers $user
 */
class Permissions extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'permissions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'section', 'access'], 'required'],
            [['user_id'], 'integer'],
            [['access'], 'string'],
            [['section'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Adminusers::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function updateRelations(){}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'section' => 'Раздел',
            'access' => 'Тип доступа',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Adminusers::className(), ['id' => 'user_id']);
    }
}
