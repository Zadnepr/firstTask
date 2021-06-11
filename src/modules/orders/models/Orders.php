<?php

namespace app\modules\orders\models;

use Yii;
use yii\data\Pagination;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;


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
class Orders extends \yii\db\ActiveRecord
{
    use FiltersTrait;

    public $datetime, $date, $time, $username, $status_title, $mode_title, $service_title, $service_id_title;

    public function afterFind()
    {
        parent::afterFind();
        $this->username = trim($this->users->first_name . ' ' . $this->users->last_name);
        $this->service_id_title = $this->services->id . ' ' . $this->services->name;
        $this->service_title = $this->services->name;
        $this->status_title = $this->getStatusTitle();
        $this->mode_title = $this->getModeTitle();
        $this->date = $this->getDate();
        $this->time = $this->getTime();
        $this->datetime = $this->date . ' ' . $this->time;
    }

    public function getUsers()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    public function getServices()
    {
        return $this->hasOne(Services::class, ['id' => 'service_id']);
    }

    public static function getOrdersList(array $settings = []){
        $defaultSettings = [
            'filters' => [],
            'limit' => 100,
            'order' => 'id desc',
        ];

        $settings = array_merge($defaultSettings, $settings);

        $orders = self::find()
            ->leftJoin('users', '`orders`.`user_id` = `users`.`id`')
            ->with('users', 'services');

        if($settings['filters']){
            $orders = self::applyFilters($orders, $settings['filters']);
        }

        $countQuery = clone $orders;
        $totalCount = $countQuery->count();
        $pages = new Pagination(['totalCount' => $totalCount]);

        if(is_numeric($settings['limit']) and $settings['limit']>0){
            $orders->limit($settings['limit']);
            $pages->setPageSize($settings['limit']);
        }
        if($settings['order']){
            $orders->orderBy($settings['order']);
        }

        $total = ($settings['limit'] * $pages->page) + $settings['limit'];

        return [
            'pagination' => $pages,
            'model' => $orders,
            'data' => $orders->offset($pages->offset)->all(),
            'offset' => $pages->offset+1,
            'total' => $total > $totalCount ? $totalCount : $total,
            'totalCount' => $totalCount,
        ];
    }

    /**
     * Returns status title of order
     * @return string
     */
    public function getStatusTitle(){
        $Status = Statuses::findIdentityById($this->status);
        return $Status ? $Status->title : 'Undefined';
    }

    /**
     * Returns mode title of order
     * @return string
     */
    public function getModeTitle(){
        $Mode = Modes::findIdentityById($this->mode);
        return $Mode ? $Mode->title : 'Undefined';
    }

    /**
     * Returns string date of order create in format Y-m-d
     * @return date|string
     */
    public function getDate(){
        return date('Y-m-d', $this->created_at);
    }

    /**
     * Returns string time of order create in format H:i:s
     * @return date|string
     */
    public function getTime(){
        return date('H:i:s', $this->created_at);
    }

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
