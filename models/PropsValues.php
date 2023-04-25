<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "props_values".
 *
 * @property integer $id
 * @property integer $prop_id
 * @property string $name
 *
 * @property Props $prop
 */
class PropsValues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'props_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prop_id', 'name'], 'required'],
            [['prop_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['prop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Props::className(), 'targetAttribute' => ['prop_id' => 'id']],
        ];
    }

    public function updateRelations(){\Yii::$app->langs->saveTranslates($this);}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prop_id' => 'Prop ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProp()
    {
        return $this->hasOne(Props::className(), ['id' => 'prop_id']);
    }
}
