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
<?php
if ($orders && $orders->getTotalCount() > 0) : ?>
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
                        'statusId' => $statusId,
                        'serviceId' => $serviceId,
                        'modeId' => $modeId,
                        'search' => $search ? $search : null,
                        'searchType' => $search ? $searchType : null,
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
<?php
endif; ?>
