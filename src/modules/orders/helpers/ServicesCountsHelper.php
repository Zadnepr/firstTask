<?php


namespace orders\helpers;

use yii\data\ActiveDataProvider;

/**
 * Helper for count total sum of services in module orders
 */
class ServicesCountsHelper
{

    /**
     * @param ActiveDataProvider $dataProvider
     * @return float|int
     */
    public static function count(ActiveDataProvider $dataProvider)
    {

        $rows = $dataProvider->getModels();
        return array_sum(
            array_map(
                function ($service) {
                    return $service['counts'];
                },
                $rows
            )
        );
    }

}