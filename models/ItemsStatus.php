<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brands".
 *
 * @property integer $id
 * @property string $name
 */
class ItemsStatus extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'items_status';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name'], 'required'],
			[['name'], 'string'],
			[['name'], 'string', 'max' => 255],
		];
	}

	public function columnsData(){
		return [
			['class' => 'yii\grid\CheckboxColumn',
			 'checkboxOptions' => function ($model, $key, $index, $column) {
				 return ['class' => 'rowChecker', 'value' => $model->id];
			 }],

			'id',
			'name',

			['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
		];
	}

	public function fieldsData()
	{
		return [
			'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
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
			'name' => 'Название',
		];
	}

	public function getItems()
	{
		return $this->hasMany(Items::className(), ['status_id' => 'id']);
	}
}
