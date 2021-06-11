<?php

namespace app\modules\orders\models;

use Yii;
use yii\base\Model;


/**
 * This is the model class for object "statuses".
 *
 * @property int $id
 * @property string $title
 */
class Statuses extends Model
{
    public $id;
    public $title;

    private static $statuses = [
        0 => [
            'id' => 0,
            'title' => 'Pending',
        ],
        1 => [
            'id' => 1,
            'title' => 'In progress',
        ],
        2 => [
            'id' => 2,
            'title' => 'Completed',
        ],
        3 => [
            'id' => 3,
            'title' => 'Canceled',
        ],
        4 => [
            'id' => 4,
            'title' => 'Fail',
        ],
    ];

    /**
     * Returns status ids list of orders
     * @return array|string[]
     */
    public static function getStatuses(){
        return array_map(function($statuse){ return new static($statuse); }, self::$statuses);
    }
    /**
     * Returns status ids list of orders
     * @return array|string[]
     */
    public static function getStatusesIds(){
        return array_map(function($statuse){ return $statuse['id']; }, self::$statuses);
    }

    /**
     * Returns status titles list of orders
     * @return array|string[]
     */
    public static function getStatusesTitles(){
        return array_map(function($statuse){ return $statuse['title']; }, self::$statuses);
    }

    /**
     * Rules for validation
     * @return array
     */
    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'integer'],
            ['id', 'in', 'range' => self::getStatusesIds()],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityById(int $id)
    {
        foreach (self::$statuses as $statuse) {
            if ($statuse['id'] === $id) {
                return new static($statuse);
            }
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return isset(self::$statuses[$id]) ? new static(self::$statuses[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Status',
            'title' => 'Status title',
        ];
    }
}
