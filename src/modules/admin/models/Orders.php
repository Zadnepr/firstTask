<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\BaseObject;
use yii\data\Pagination;


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
            ->select('`orders`.*')
            ->leftJoin('users', '`orders`.`user_id` = `users`.`id`')
            ->with('users', 'services');

        if($settings['filters']){
            if(isset($settings['filters']['status'])) {
                $orders->andWhere(['status' => $settings['filters']['status']]);
            }
            if(isset($settings['filters']['service'])) {
                $orders->andWhere(['service_id' => $settings['filters']['service']]);
            }
            if(isset($settings['filters']['search']) and isset($settings['filters']['searchType'])) {
                switch($settings['filters']['searchType']){
                    case 1: $searchAttribute = '`order`.`id`'; break;
                    case 2: $searchAttribute = 'link'; break;
                    case 3: $searchAttribute = 'CONCAT_WS(\' \', `users`.`first_name`, `users`.`last_name`)'; break;
                }
                $orders->andWhere(['like', $searchAttribute, $settings['filters']['search'] ]);
            }
        }
        //var_dump($orders->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);

        $countQuery = clone $orders;
        $totalCount = $countQuery->count();
        $pages = new Pagination(['totalCount' => $totalCount]);

        if(is_numeric($settings['limit']) and $settings['limit']>0){
            $orders->limit($settings['limit']);
            $pages->setPageSize($settings['limit']);
        }
        if(
            $settings['order']
            and in_array(current(explode(' ', $settings['order'])), array_keys(self::attributeLabels()))
            and in_array(strtolower(end(explode(' ', $settings['order']))), ['desc', 'asc'])
            and count(explode(' ', $settings['order']))==2
        ){
            $orders->orderBy($settings['order']);
        }

        $result = $orders->offset($pages->offset)->all();
        $total = ($settings['limit'] * $pages->page) + $settings['limit'];

        return [
            'pagination' => $pages,
            'data' => $result,
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
        switch($this->status){
            case '0': $statusTitle = 'Pending'; break;
            case '1': $statusTitle = 'In progress'; break;
            case '2': $statusTitle = 'Completed'; break;
            case '3': $statusTitle = 'Canceled'; break;
            case '4': $statusTitle = 'Fail'; break;
            default: $statusTitle = 'Undefined'; break;
        }
        return $statusTitle;
    }

    /**
     * Returns mode title of order
     * @return string
     */
    public function getModeTitle(){
        switch($this->mode){
            case '0': $modeTitle = 'Manual'; break;
            case '1': $modeTitle = 'Auto'; break;
            default: $modeTitle = 'Undefined'; break;
        }
        return $modeTitle;
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
