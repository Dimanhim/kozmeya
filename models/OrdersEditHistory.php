<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders_edithistory".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $adminuser_id
 * @property string $date
 *
 * @property Adminusers $adminuser
 * @property Orders $order
 */
class OrdersEditHistory extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders_edithistory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'adminuser_id'], 'required'],
            [['order_id', 'adminuser_id'], 'integer'],
            [['date'], 'safe'],
            [['adminuser_id'], 'exist', 'skipOnError' => true, 'targetClass' => Adminusers::className(), 'targetAttribute' => ['adminuser_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'adminuser_id' => 'Adminuser ID',
            'date' => 'Date',
        ];
    }

    public function updateRelations(){}
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminuser()
    {
        return $this->hasOne(Adminusers::className(), ['id' => 'adminuser_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }
}
