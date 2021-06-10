<?php


namespace app\modules\admin\models;


use yii\base\Model;

class SearchForm extends Model
{
    public $search;
    public $searchType;

    public function rules()
    {
        return [
            [['search', 'searchType'], 'required'],
            ['search', 'string', 'length' => [4, 24]],
            ['searchType', 'in', 'range' => [1, 2, 3]],
        ];
    }


}