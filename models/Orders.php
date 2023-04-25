<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property string $date
 * @property integer $pickup_point_id
 * @property integer $status_id
 * @property integer $payment_id
 * @property integer $delivery_id
 * @property string $promocode
 * @property double $percent
 * @property double $delivery_price
 * @property double $adding_price
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $comment
 * @property integer $deleted
 *
 * @property OrdersStatuses $status
 * @property Payments $payment
 * @property Deliveries $delivery
 * @property Pickuppoints $pickupPoint
 * @property OrdersItems[] $ordersItems
 */
class Orders extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'payment_date', 'delivery_date', 'delivery_time', 'delivery_time_range'], 'safe'],
            [['pickup_point_id', 'status_id', 'payment_id', 'payment_invoice_id', 'delivery_id', 'deleted'], 'integer'],
            [['payment_id', 'delivery_id'], 'required'],
            [['discount_value', 'discount_type', 'delivery_price', 'adding_price'], 'number'],
            [['address', 'comment', 'adding_price_text'], 'string'],
            [['promocode', 'name', 'email', 'phone'], 'string', 'max' => 255],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrdersStatuses::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payments::className(), 'targetAttribute' => ['payment_id' => 'id']],
            [['delivery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Deliveries::className(), 'targetAttribute' => ['delivery_id' => 'id']],
            [['pickup_point_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pickuppoints::className(), 'targetAttribute' => ['pickup_point_id' => 'id']],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Дата создания',
            'pickup_point_id' => 'Пункт самовывоза',
            'status_id' => 'Статус',
            'payment_id' => 'Метод оплаты',
            'delivery_id' => 'Тип доставки',
            'promocode' => 'Промокод',
            'discount_value' => 'Скидка',
            'discount_type' => 'Тип скидки',
            'delivery_price' => 'Стоимость доставки',
            'delivery_date' => 'Дата доставки',
            'delivery_time' => 'Время доставки',
            'delivery_time_range' => 'Примерное время доставки',
            'adding_price' => 'Добавочная стоимость',
            'name' => 'Имя',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'address' => 'Адрес',
            'comment' => 'Комментарий',
            'deleted' => 'Удален',
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        $data = Yii::$app->request->post();

        if(!$insert) {
            if(!Yii::$app->user->isGuest) {
                \Yii::$app->db->createCommand()->insert('orders_edithistory', ['order_id' => $this->id, 'adminuser_id' => Yii::$app->user->identity->id])->execute();
            }

            $this->changeStatus($this, $changedAttributes);
        }
        else {

        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $data = Yii::$app->request->post();

            return true;
        }
        return false;
    }

    private function changeStatus($model, $changedAttributes){
        if(!$model->isNewRecord) {
            if(isset($changedAttributes["status_id"]) && $changedAttributes["status_id"] != "" && $model->status_id != $changedAttributes["status_id"]) {
                $oldStatus = OrdersStatuses::findOne($changedAttributes["status_id"]);

                if(isset(\Yii::$app->params['settingsForms']["sms"]["actives"]["on_order_status"], \Yii::$app->params['settingsForms']["sms"]["msg"]["on_order_status"]) && \Yii::$app->params['settingsForms']["sms"]["actives"]["on_order_status"]){
                    if(\Yii::$app->params['settingsForms']["sms"]["msg"]["on_order_status"] != "" && $model->phone != "") {
                        $msg = $this->regenTemplate(\Yii::$app->params['settingsForms']["sms"]["msg"]["on_order_status"], ['oldStatus' => $oldStatus]);

                        Yii::$app->sms->send($model->phone, $msg);
                    }
                }

                if($model->email != "") {
                    $subject = (isset(\Yii::$app->params['settingsForms']["email_templates"]["on_order_status_subject"]) ? $this->regenTemplate(\Yii::$app->params['settingsForms']["email_templates"]["on_order_status_subject"]) : "Заказ №".$model->id." изменен!");
                    $msg = (isset(\Yii::$app->params['settingsForms']["email_templates"]["on_order_status"]) ? $this->regenTemplate(\Yii::$app->params['settingsForms']["email_templates"]["on_order_status"], ['oldStatus' => $oldStatus]) : "Статус Вашего заказа №".$model->id." изменен на ".$model->status->name);

                    Yii::$app->mailer->compose(['html' => 'order'],['model' => $model, 'text' => $msg])
                        ->setTo(explode(",", $model->email))
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['HOST']])
                        ->setSubject($subject)
                        ->send();
                }
            }
        }
    }

    public function regenTemplate($msg, $data = []){
        $prices = \Yii::$app->catalog->orderPrice($this);

        $msg = str_replace("{id}", $this->id, $msg);
        if(isset($data["oldStatus"])) $msg = str_replace("{status_name_old}", $data["oldStatus"]->name, $msg);
        $msg = str_replace("{status_name_new}", $this->status->name, $msg);
        $msg = str_replace("{name}", $this->name, $msg);
        $msg = str_replace("{price}", \Yii::$app->functions->getPrice($prices["result_price"]+$this->delivery_price+$this->adding_price), $msg);

        return $msg;
    }

    public function updateRelations(){
        $data = Yii::$app->request->post();

        if(isset($data["OrdersItems"]["id"]) && count($data["OrdersItems"]["id"]) > 0) {
            \Yii::$app->db->createCommand()->delete('orders_items', ['order_id' => $this->id,])->execute();

            foreach($data["OrdersItems"]["id"] as $index => $id){
                $orderitems = new OrdersItems();
                $orderitems->order_id = $this->id;
                $orderitems->item_id = $data["OrdersItems"]["item_id"][$index];
                $orderitems->name = $data["OrdersItems"]["name"][$index];
                $orderitems->qty = $data["OrdersItems"]["qty"][$index];
                $orderitems->price = $data["OrdersItems"]["price"][$index];
                $orderitems->save();
            }
        }
    }

    public function searchData(){
        return [
            's' => ['label' => 'Поиск', 'type' => 'fulltext'],
            'date' => ['label' => 'Дата', 'type' => 'daterange'],
            'statuses' => ['label' => 'Статусы', 'type' => 'select',  'values' => \app\models\OrdersStatuses::find()->select(["id", "name"])->orderBy("name DESC")->all()],
            'deliveries' => ['label' => 'Типы доставки', 'type' => 'select',  'values' => \app\models\Deliveries::find()->select(["id", "name"])->orderBy("name DESC")->all()],
            'payments' => ['label' => 'Методы оплаты', 'type' => 'select',  'values' => \app\models\Payments::find()->select(["id", "name"])->orderBy("name DESC")->all()],
            'deleted' => ['label' => 'Удаленные', 'type' => 'checkbox'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(OrdersStatuses::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payments::className(), ['id' => 'payment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDelivery()
    {
        return $this->hasOne(Deliveries::className(), ['id' => 'delivery_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPickupPoint()
    {
        return $this->hasOne(Pickuppoints::className(), ['id' => 'pickup_point_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(OrdersItems::className(), ['order_id' => 'id']);
    }

    public function getComments()
    {
        return $this->hasMany(OrdersComments::className(), ['order_id' => 'id']);
    }

    public function getHistory()
    {
        return $this->hasMany(OrdersEditHistory::className(), ['order_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
