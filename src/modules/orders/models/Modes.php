<?php

namespace app\modules\orders\models;

use Yii;
use yii\base\Model;


/**
 * This is the model class for object "Modes".
 *
 * @property int $id
 * @property string $title
 */
class Modes extends Model
{
    public $id;
    public $title;

    private static $modes = [
        0 => [
            'id' => 0,
            'title' => 'Manual',
        ],
        1 => [
            'id' => 1,
            'title' => 'Auto',
        ],
    ];

    /**
     * Returns status ids list of orders
     * @return array|string[]
     */
    public static function getModes(){
        return array_map(function($mode){ return new static($mode); }, self::$modes);
    }
    /**
     * Returns status ids list of orders
     * @return array|string[]
     */
    public static function getModesIds(){
        return array_map(function($mode){ return $mode['id']; }, self::$modes);
    }

    /**
     * Returns status titles list of orders
     * @return array|string[]
     */
    public static function getModesTitles(){
        return array_map(function($mode){ return $mode['title']; }, self::$modes);
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
            ['id', 'in', 'range' => self::getModesIds()],
        ];
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
