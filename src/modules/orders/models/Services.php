<?php

namespace orders\models;

use orders\models\search\ServicesSearch;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property string $name
 *
 * extended
 * @property int $counts
 */
class Services extends ActiveRecord
{
    public int $counts;

    /**
     * @return array
     */
    public static function getServicesIds(): array
    {
        return ServicesSearch::search();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'counts' => 'Counts',
        ];
    }
}
