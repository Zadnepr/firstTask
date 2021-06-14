<?php


namespace app\modules\orders\helpers;


use yii\data\ActiveDataProvider;

class ServicesCounts
{

    public static function count(ActiveDataProvider $dataProvider){
        $rows = $dataProvider->getModels();
        return array_sum(array_map(function($service){ return $service['counts']; }, $rows));
    }

}