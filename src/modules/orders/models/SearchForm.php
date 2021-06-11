<?php


namespace app\modules\orders\models;


use yii\base\Model;

class SearchForm extends Model
{
    public $search;
    public $searchType;

    public $id;
    public $title;
    public $field;

    private static $serchTypes = [
        1 => [
            'id' => 1,
            'title' => 'Order ID',
            'field' => '`orders`.`id`',
        ],
        2 => [
            'id' => 2,
            'title' => 'Link',
            'field' => '`link`',
        ],
        3 => [
            'id' => 3,
            'title' => 'Username',
            'field' => 'CONCAT_WS(\' \', `users`.`first_name`, `users`.`last_name`)',
        ],
    ];

    /**
     * Returns types objects
     * @return array|string[]
     */
    public static function getTypes(){
        return array_map(function($type){ return new static($type); }, self::$serchTypes);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityById(int $id)
    {
        foreach (self::$serchTypes as $type) {
            if ($type['id'] === $id) {
                return new static($type);
            }
        }
        return null;
    }

    public function rules()
    {
        return [
            [['search', 'searchType'], 'required'],
            ['search', 'string', 'length' => [4, 24]],
            ['searchType', 'in', 'range' => [1, 2, 3]],
        ];
    }


}