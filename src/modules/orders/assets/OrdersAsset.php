<?php

namespace orders\assets;

use yii\web\AssetBundle;

/**
 * Orders module asset bundle.
 */
class OrdersAsset extends AssetBundle
{
    public $basePath = '@orders';
    public $baseUrl = '/modules/orders/views/';
    public $css = [
        'css/orders.css'
    ];
    public $js = [
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
