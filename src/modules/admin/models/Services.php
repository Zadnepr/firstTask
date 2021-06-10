<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property string $name
 */
class Services extends \yii\db\ActiveRecord
{
    public $counts;

    public static function getServicesList(array $settings = []){
        $defaultSettings = [
            'filters' => [],
            'limit' => 20,
            'order' => 'counts desc',
        ];

        $settings = array_merge($defaultSettings, $settings);

        $services = self::find()
            ->select('`services`.*, COUNT(`services`.`id`) as `counts`')
            ->leftJoin('orders', '`orders`.`service_id` = `services`.`id`')
            ->leftJoin('users', '`orders`.`user_id` = `users`.`id`')
            ->groupBy('`services`.`id`');

        if($settings['filters']){
            if(isset($settings['filters']['status'])) {
                $services->where(['status' => $settings['filters']['status']]);
            }
            if(isset($settings['filters']['search']) and isset($settings['filters']['searchType'])) {
                switch($settings['filters']['searchType']){
                    case 1: $searchAttribute = 'id'; break;
                    case 2: $searchAttribute = 'link'; break;
                    case 3: $searchAttribute = 'CONCAT_WS(\' \', `users`.`first_name`, `users`.`last_name`)'; break;
                }
                $services->where(['like', $searchAttribute, '%' . $settings['filters']['search'] . '%', false ]);
            }
        }

        if(is_numeric($settings['limit']) and $settings['limit']>0){
            $services->limit($settings['limit']);
        }
        if(
            $settings['order']
            and in_array(current(explode(' ', $settings['order'])), array_keys(self::attributeLabels()))
            and in_array(strtolower(end(explode(' ', $settings['order']))), ['desc', 'asc'])
            and count(explode(' ', $settings['order']))==2
        ){
            $services->orderBy($settings['order']);
        }

        return $services->all();
    }

    public static function getServicesIds(){
        return self::find()->select('id')->asArray()->column();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'counts' => 'Counts',
        ];
    }
}
