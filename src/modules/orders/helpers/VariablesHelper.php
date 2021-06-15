<?php


namespace orders\helpers;

/**
 * Helper for count total sum of services in module orders
 */
class VariablesHelper
{

    /**
     * @param null $variable
     * @return int|null
     */
    public static function intNull($variable = null)
    {
        return (is_null($variable) and is_numeric($variable)) ? null : (int)$variable;
    }

}