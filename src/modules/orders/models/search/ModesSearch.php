<?php

namespace orders\models\search;

use yii\base\BaseObject;


/**
 * This is the model class for object "ModesSearch".
 *
 * @const MODE_MANUAL
 * @const MODE_AUTO
 * @property int $id
 * @property string $title
 * @property array $modes
 */
class ModesSearch extends BaseObject
{
    public const MODE_MANUAL = 0;
    public const MODE_AUTO = 1;
    public int $id;
    public string $title;
    private static array $modes = [
        [
            'id' => self::MODE_MANUAL,
            'title' => 'mode.manual',
        ],
        [
            'id' => self::MODE_AUTO,
            'title' => 'mode.auto',
        ],
    ];

    /**
     * Returns status ids list of orders
     * @return array|string[]
     */
    public static function getModes(): array
    {
        return array_map(
            function ($mode) {
                return new static($mode);
            },
            self::$modes
        );
    }

    /**
     * Returns status titles list of orders
     * @return array|string[]
     */
    public static function getModesTitles(): array
    {
        return array_map(
            function ($mode) {
                return $mode['title'];
            },
            self::$modes
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityById(int $id): ?ModesSearch
    {
        foreach (self::$modes as $mode) {
            if ($mode['id'] === $id) {
                return new static($mode);
            }
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): ?ModesSearch
    {
        return isset(self::$modes[$id]) ? new static(self::$modes[$id]) : null;
    }

    /**
     * Returns status ids list of orders
     * @return array|string[]
     */
    public static function getModesIds(): array
    {
        return array_map(
            function ($mode) {
                return $mode['id'];
            },
            self::$modes
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
            'id' => 'Mode',
            'title' => 'Mode title',
        ];
    }
}
