<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "translates".
 *
 * @property integer $id
 * @property integer $land_id
 * @property string $model
 * @property integer $post_id
 * @property string $field
 * @property string $value
 *
 * @property Langs $land
 */
class Translates extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'translates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['land_id', 'model', 'post_id', 'field', 'value'], 'required'],
            [['land_id', 'post_id'], 'integer'],
            [['value'], 'string'],
            [['model', 'field'], 'string', 'max' => 255],
            [['land_id'], 'exist', 'skipOnError' => true, 'targetClass' => Langs::className(), 'targetAttribute' => ['land_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLand()
    {
        return $this->hasOne(Langs::className(), ['id' => 'land_id']);
    }
}
