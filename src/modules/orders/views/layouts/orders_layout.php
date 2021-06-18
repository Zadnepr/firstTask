<?php

/* @var $this View */

/* @var $content string */

use orders\assets\OrdersAsset;
use yii\helpers\Html;
use yii\web\View;

OrdersAsset::register($this);
?>
<?php
$this->beginPage() ?>

    <!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php
        $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php
        $this->head() ?>
        <style>
            .label-default {
                border: 1px solid #ddd;
                background: none;
                color: #333;
                min-width: 30px;
                display: inline-block;
            }
        </style>
    </head>
    <body>
    <?php
    $this->beginBody() ?>
    <nav class="navbar navbar-fixed-top navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-navbar-collapse">
                    <span class="sr-only"><?= Yii::t('orders/main', 'global.global.toggle-navigation') ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bs-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="/orders"><?= Yii::t('orders/main', 'global.orders') ?></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <?= $content ?>

    </div>
    <?php
    $this->endBody() ?>
    </body>
    <html>
<?php
$this->endPage() ?>