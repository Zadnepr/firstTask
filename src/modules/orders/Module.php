<?php

namespace app\modules\orders;

use Yii;

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
        $this->registerTranslations();
        //Yii::$app->language = 'ru-RU';
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/orders/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@orders/messages',
            'fileMap' => [
                'modules/orders/main' => 'main.php',
            ],
        ];
    }

}
