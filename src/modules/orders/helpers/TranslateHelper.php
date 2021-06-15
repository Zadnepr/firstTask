<?php


namespace orders\helpers;

use yii;

/**
 * Helper for translation in module orders
 */
class TranslateHelper
{

    /**
     * @param string $category
     * @param string $message
     * @param mixed $params
     * @param string|null $language
     * @return string
     */
    public static function t(string $category, string $message, $params = [], string $language = null)
    {
        return Yii::t('modules/orders/' . $category, $message, $params, $language);
    }

}