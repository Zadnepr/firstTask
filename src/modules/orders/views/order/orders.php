<?php

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
?>

<?= $this->render(
    '@orders/views/order/navigation',
    compact('status_id', 'statuses', 'search', 'searchType', 'search_types')
) ?>

<?= Errors::widget(['errors' => $errors]) ?>

    <table class="table order-table">
        <thead>
        <tr>
            <th><?= Yii::t(Yii::getAlias('@translateOrders'), 'table.id') ?></th>
            <th><?= Yii::t(Yii::getAlias('@translateOrders'), 'table.user') ?></th>
            <th><?= Yii::t(Yii::getAlias('@translateOrders'), 'table.link') ?></th>
            <th><?= Yii::t(Yii::getAlias('@translateOrders'), 'table.quantity') ?></th>
            <th class="dropdown-th">
                <?= $this->render(
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
            <th><?= Yii::t(Yii::getAlias('@translateOrders'), 'table.status') ?></th>
            <th class="dropdown-th">
                <?= $this->render(
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
            <th><?= Yii::t(Yii::getAlias('@translateOrders'), 'table.created') ?></th>
        </tr>
        </thead>
        <tbody>
        <?= $this->render('@orders/views/order/ordersList', compact('orders')) ?>
        </tbody>
    </table>
<?= $this->render(
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