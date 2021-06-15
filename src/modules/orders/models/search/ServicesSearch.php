<?php


namespace orders\models\search;


use orders\models\Services;

class ServicesSearch
{

    /**
     * @return array
     */
    public static function search()
    {
        return Services::find()->select('id')->asArray()->column();
    }

}