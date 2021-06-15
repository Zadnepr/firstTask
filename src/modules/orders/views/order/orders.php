<?php

use orders\helpers\TranslateHelper;
use orders\widgets\Errors;

/**
 * @var $search : search query
 * @var $searchType : Type of search
 * @var $status_id : selected status
 * @var $service_id : selected service
 * @var $mode_id : selected mode
 * @var $orders : Object ActiveDataProvider with Orders list
 * @var $services : Object ActiveDataProvider with Services list
 * @var $statuses : Array of StatusesSearch
 * @var $modes : Array of ModesSearch
 * @var $search_types : Array of search types
 * @var $errors : Validation Errors
 */

$this->registerJsFile(
    '/modules/orders/views/js/jquery.min.js',
);
$this->registerJsFile(
    '/modules/orders/views/js/bootstrap.min.js',
);
$this->registerCssFile(
    '/modules/orders/views/css/bootstrap.min.css',
);
$this->registerCssFile(
    '/modules/orders/views/css/custom.css',
);
?>

<?php
echo $this->render(
    '@orders/views/order/navigation',
    compact('status_id', 'statuses', 'search', 'searchType', 'search_types')
) ?>

<?= Errors::widget(['errors' => $errors]) ?>

    <table class="table order-table">
        <thead>
        <tr>
            <th><?= TranslateHelper::t('main', 'table.id') ?></th>
            <th><?= TranslateHelper::t('main', 'table.user') ?></th>
            <th><?= TranslateHelper::t('main', 'table.link') ?></th>
            <th><?= TranslateHelper::t('main', 'table.quantity') ?></th>
            <th class="dropdown-th">
                <?php
                echo $this->render(
                    '@orders/views/order/servicesDropdown',
                    compact(
                        'services',
                        'status_id',
                        'service_id',
                        'mode_id',
                        'search',
                        'searchType',
                        'search_types'
                    )
                ) ?>
            </th>
            <th><?= TranslateHelper::t('main', 'table.status') ?></th>
            <th class="dropdown-th">
                <?php
                echo $this->render(
                    '@orders/views/order/modesDropdown',
                    compact(
                        'modes',
                        'status_id',
                        'service_id',
                        'mode_id',
                        'search',
                        'searchType',
                        'search_types'
                    )
                ) ?>
            </th>
            <th><?= TranslateHelper::t('main', 'table.created') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        echo $this->render('@orders/views/order/ordersList', compact('orders')) ?>
        </tbody>
    </table>
<?php
echo $this->render(
    '@orders/views/order/pagination',
    compact(
        'orders',
        'services',
        'status_id',
        'service_id',
        'mode_id',
        'search',
        'searchType',
        'search_types'
    )
) ?>