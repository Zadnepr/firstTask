<?php

namespace app\modules\orders\models;

use Yii;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property string $name
 */
class Services extends \yii\db\ActiveRecord
{
    use FiltersTrait;

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
            unset($settings['filters']['service']);
            $services = self::applyFilters($services, $settings['filters']);
        }

        if(is_numeric($settings['limit']) and $settings['limit']>0){
            $services->limit($settings['limit']);
        }
        if($settings['order']){
            $services->orderBy($settings['order']);
        }

        $result = [
            'model' => $services,
            'data' => $services->all()
        ];
        $result['sum'] = array_sum(array_map(function($service){ return $service['counts']; }, $result['data']));
        return $result;
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
