<?php

namespace orders\models;

use orders\models\search\ModesSearch;
use orders\models\search\StatusesSearch;
use yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


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
 *
 * extended afterFind
 * @property string $datetime
 * @property string $date
 * @property string $time
 * @property string $username
 * @property string $statusTitle
 * @property string $modeTitle
 * @property string $serviceTitle
 * @property int $serviceId
 */
class Orders extends ActiveRecord
{
    public string $datetime;
    public string $date;
    public string $time;
    public string $username;
    public string $statusTitle;
    public string $modeTitle;
    public string $serviceTitle;
    public int $serviceId;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
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
        $this->serviceId = $this->services->id;
        $this->serviceTitle = $this->services->name;
        $this->statusTitle = Yii::t('orders/main', $this->getStatusTitle());
        $this->modeTitle = Yii::t('orders/main', $this->getModeTitle());
        $this->date = $this->getDate();
        $this->time = $this->getTime();
        $this->datetime = $this->date . ' ' . $this->time;
    }

    /**
     * Returns status title of order
     * @return string
     */
    public function getStatusTitle(): string
    {
        $status = StatusesSearch::findIdentityById($this->status);
        return $status ? $status->title : 'Undefined';
    }

    /**
     * Returns mode title of order
     * @return string
     */
    public function getModeTitle(): string
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
    public function getUsers(): ActiveQuery
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getServices(): ActiveQuery
    {
        return $this->hasOne(Services::class, ['id' => 'service_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
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
    public function attributeLabels(): array
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
