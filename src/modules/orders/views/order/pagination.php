<?php

use orders\helpers\TranslateHelper;
use orders\widgets\PaginationCounters;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var $search : search query
 * @var $searchType : Type of search
 * @var $status_id : selected status
 * @var $service_id : selected service
 * @var $mode_id : selected mode
 * @var $orders : Object ActiveDataProvider with Orders list
 * @var $services : Object ActiveDataProvider with Services list
 * @var $statuses : Array of StatusesSearch
 * @var $search_types : Array of search types
 */

?>

<div class="row">
    <div class="col-sm-8">
        <nav>
            <?= LinkPager::widget(['pagination' => $orders->getPagination()]) ?>
        </nav>
    </div>
    <div class="col-sm-4 pagination-counters">
        <?= PaginationCounters::widget(['orders' => $orders]) ?>
    </div>
    <div class="col-sm-12">
        <?php
        echo Html::a(
            TranslateHelper::t('main', 'global.save-results'),
            Url::toRoute(
                [
                    '/orders',
                    'status_id' => $status_id,
                    'service_id' => $service_id,
                    'mode_id' => $mode_id,
                    'search' => $search,
                    'searchType' => $searchType,
                    'download' => 1
                ]
            ),
            [
                'class' => 'btn btn-primary pull-right',
                'title' => TranslateHelper::t('main', 'global.save-results'),
            ]
        );
        ?>
    </div>
</div>

