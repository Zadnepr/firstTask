<?php

namespace orders\models\search;

use yii\base\BaseObject;


/**
 * This is the model class for object "statuses".
 *
 * @const STATUS_PENDING
 * @const STATUS_IN_PROGRESS
 * @const STATUS_COMPLETED
 * @const STATUS_FAIL
 * @property int $id
 * @property string $title
 * @property array $statuses
 */
class StatusesSearch extends BaseObject
{
    public const STATUS_PENDING = 0;
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_CANCELED = 3;
    public const STATUS_FAIL = 4;
    private static array $statuses = [
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
    public int $id;
    public string $title;

    /**
     * Returns statuses objects
     * @return array|string[]
     */
    public static function getStatuses(): array
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
    public static function getStatusesTitles(): array
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
    public static function findIdentityById(int $id): ?StatusesSearch
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
    public static function findIdentity($id): ?StatusesSearch
    {
        return isset(self::$statuses[$id]) ? new static(self::$statuses[$id]) : null;
    }

    /**
     * Rules for validation
     * @return array
     */
    public function rules(): array
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
    public static function getStatusesIds(): array
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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('orders/main', 'Status'),
            'title' => Yii::t('orders/main', 'Status title'),
        ];
    }
}
