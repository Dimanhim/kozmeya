<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "settings_forms".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $form_data
 */
class SettingsForms extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_forms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['form_data'], 'string'],
            [['code', 'name'], 'string', 'max' => 255],
        ];
    }

    public function updateRelations(){}

    /**
     * @inheritdoc
     */

    public function columnsData(){
        return [
            'id',
            'name',
            'code',

            ['class' => 'yii\grid\ActionColumn','template' => '{update}'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код',
            'name' => 'Наименование',
            'form_data' => 'Form Data',
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        $data = Yii::$app->request->post();
    }
}
