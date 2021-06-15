<?php

namespace orders\models\search;

use orders\helpers\TranslateHelper;
use yii\base\BaseObject;


/**
 * This is the model class for object "statuses".
 *
 * @property int $id
 * @property string $title
 */
class StatusesSearch extends BaseObject
{
    private static $statuses = [
        0 => [
            'id' => 0,
            'title' => 'status.pending',
        ],
        1 => [
            'id' => 1,
            'title' => 'status.in-progress',
        ],
        2 => [
            'id' => 2,
            'title' => 'status.completed',
        ],
        3 => [
            'id' => 3,
            'title' => 'status.canceled',
        ],
        4 => [
            'id' => 4,
            'title' => 'status.fail',
        ],
    ];
    public $id;
    public $title;

    /**
     * StatusesSearch constructor.
     * @param array $config
     */
    function __construct($config = [])
    {
        parent::__construct($config);
        foreach (self::$statuses as &$status) {
            $status['title'] = TranslateHelper::t('main', $status['title']);
        }
    }

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
            'id' => TranslateHelper::t('main', 'Status'),
            'title' => TranslateHelper::t('main', 'Status title'),
        ];
    }
}
