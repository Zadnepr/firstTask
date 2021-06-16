<?php

use orders\widgets\PaginationCountersWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var $search : search query
 * @var $searchType : Type of search
 * @var $statusId : selected status
 * @var $serviceId : selected service
 * @var $modeId : selected mode
 * @var $orders : Object ActiveDataProvider with Orders list
 */

?>

<div class="row">
    <div class="col-sm-8">
        <nav>
            <?= LinkPager::widget(['pagination' => $orders->getPagination()]) ?>
        </nav>
    </div>
    <div class="col-sm-4 pagination-counters">
        <?= PaginationCountersWidget::widget(['orders' => $orders]) ?>
    </div>
    <div class="col-sm-12">
        <?php
        echo Html::a(
            Yii::t('orders/main', 'global.save-results'),
            Url::toRoute(
                [
                    '/orders/order/download',
                    'status_id' => $statusId,
                    'service_id' => $serviceId,
                    'mode_id' => $modeId,
                    'search' => $search,
                    'searchType' => $searchType,
                ]
            ),
            [
                'class' => 'btn btn-primary pull-right',
                'title' => Yii::t('orders/main', 'global.save-results'),
            ]
        );
        ?>
    </div>
</div>

