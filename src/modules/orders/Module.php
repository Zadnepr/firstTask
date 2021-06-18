<?php

namespace orders;
use yii;

/**
 * orders module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'orders\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->defaultRoute = 'order';
        Yii::$app->errorHandler->errorAction = 'orders/order/error';
    }
}
