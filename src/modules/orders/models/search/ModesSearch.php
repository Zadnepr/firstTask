<?php

namespace orders\models\search;

use orders\helpers\TranslateHelper;
use yii\base\BaseObject;


/**
 * This is the model class for object "ModesSearch".
 *
 * @property int $id
 * @property string $title
 */
class ModesSearch extends BaseObject
{
    private static $modes = [
        0 => [
            'id' => 0,
            'title' => 'mode.manual',
        ],
        1 => [
            'id' => 1,
            'title' => 'mode.auto',
        ],
    ];
    public $id;
    public $title;

    /**
     * ModesSearch constructor.
     * @param array $config
     */
    function __construct($config = [])
    {
        parent::__construct($config);
        foreach (self::$modes as &$mode) {
            $mode['title'] = TranslateHelper::t('main', $mode['title']);
        }
    }


    /**
     * Returns status ids list of orders
     * @return array|string[]
     */
    public static function getModes()
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
    public static function getModesTitles()
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
    public static function findIdentityById(int $id)
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
    public static function findIdentity($id)
    {
        return isset(self::$modes[$id]) ? new static(self::$modes[$id]) : null;
    }

    /**
     * Returns status ids list of orders
     * @return array|string[]
     */
    public static function getModesIds()
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
            'id' => 'Mode',
            'title' => 'Mode title',
        ];
    }
}
