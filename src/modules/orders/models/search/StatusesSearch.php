<?php

namespace orders\models\search;

use yii\base\BaseObject;


/**
 * This is the model class for object "statuses".
 *
 * @property int $id
 * @property string $title
 */
class StatusesSearch extends BaseObject
{
    public const STATUS_PENDING = 0;
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_CANCELED = 3;
    public const STATUS_FAIL = 4;
    public $id;
    public $title;
    private static $statuses = [
        [
            'id' => self::STATUS_PENDING,
            'title' => 'status.pending',
        ],
        [
            'id' => self::STATUS_IN_PROGRESS,
            'title' => 'status.in-progress',
        ],
        [
            'id' => self::STATUS_COMPLETED,
            'title' => 'status.completed',
        ],
        [
            'id' => self::STATUS_CANCELED,
            'title' => 'status.canceled',
        ],
        [
            'id' => self::STATUS_FAIL,
            'title' => 'status.fail',
        ],
    ];

    /**
     * Returns statuses objects
     * @return array|string[]
     */
    public static function getStatuses()
    {
        return array_map(
            function ($statuse) {
                return new static($statuse);
            },
            self::$statuses
        );
    }

    /**
     * Returns status titles list of orders
     * @return array|string[]
     */
    public static function getStatusesTitles()
    {
        return array_map(
            function ($statuse) {
                return $statuse['title'];
            },
            self::$statuses
        );
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
     * Returns status ids list of orders
     * @return array|string[]
     */
    public static function getStatusesIds()
    {
        return array_map(
            function ($statuse) {
                return $statuse['id'];
            },
            self::$statuses
        );
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
            'id' => Yii::t('orders/main', 'Status'),
            'title' => Yii::t('orders/main', 'Status title'),
        ];
    }
}
