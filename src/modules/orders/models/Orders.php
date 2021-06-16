<?php

namespace orders\models;

use orders\models\search\ModesSearch;
use orders\models\search\StatusesSearch;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii;


/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $user_id
 * @property string $link
 * @property int $quantity
 * @property int $service_id
 * @property int $status 0 - Pending, 1 - In progress, 2 - Completed, 3 - Canceled, 4 - Fail
 * @property int $created_at
 * @property int $mode 0 - Manual, 1 - Auto
 */
class Orders extends ActiveRecord
{
    public $datetime, $date, $time, $username, $status_title, $mode_title, $service_title, $service_id_title;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->username = trim($this->users->first_name . ' ' . $this->users->last_name);
        $this->service_id_title = $this->services->id . ' ' . $this->services->name;
        $this->service_title = $this->services->name;
        $this->status_title = Yii::t('orders/main', $this->getStatusTitle());
        $this->mode_title = Yii::t('orders/main', $this->getModeTitle());
        $this->date = $this->getDate();
        $this->time = $this->getTime();
        $this->datetime = $this->date . ' ' . $this->time;
    }

    /**
     * Returns status title of order
     * @return string
     */
    public function getStatusTitle()
    {
        $status = StatusesSearch::findIdentityById($this->status);
        return $status ? $status->title : 'Undefined';
    }

    /**
     * Returns mode title of order
     * @return string
     */
    public function getModeTitle()
    {
        $mode = ModesSearch::findIdentityById($this->mode);
        return $mode ? $mode->title : 'Undefined';
    }

    /**
     * Returns string date of order create in format Y-m-d
     * @return date|string
     */
    public function getDate()
    {
        return date('Y-m-d', $this->created_at);
    }

    /**
     * Returns string time of order create in format H:i:s
     * @return date|string
     */
    public function getTime()
    {
        return date('H:i:s', $this->created_at);
    }

    /**
     * @return ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getServices()
    {
        return $this->hasOne(Services::class, ['id' => 'service_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'link', 'quantity', 'service_id', 'status', 'created_at', 'mode'], 'required'],
            [['user_id', 'quantity', 'service_id', 'status', 'created_at', 'mode'], 'integer'],
            [['link'], 'string', 'max' => 300],
            [['statusTitle'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'link' => 'Link',
            'quantity' => 'Quantity',
            'service_id' => 'Service ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'mode' => 'Mode',
        ];
    }
}
