<?php

use orders\widgets\ErrorsWidget;

/**
 * @var $search : search query
 * @var $searchType : Type of search
 * @var $statusId : selected status
 * @var $serviceId : selected service
 * @var $modeId : selected mode
 * @var $orders : Object ActiveDataProvider with Orders list
 * @var $services : Object ActiveDataProvider with Services list
 * @var $statuses : Array of StatusesSearch
 * @var $modes : Array of ModesSearch
 * @var $searchTypes : Array of search types
 * @var $errors : Validation ErrorsWidget
 */
?>

<?= $this->render(
    'layouts/navigation',
    compact('statusId', 'statuses', 'search', 'searchType', 'searchTypes')
) ?>

<?= ErrorsWidget::widget(['errors' => $errors]) ?>

    <table class="table order-table">
        <thead>
        <tr>
            <th><?= Yii::t('orders/main', 'table.id') ?></th>
            <th><?= Yii::t('orders/main', 'table.user') ?></th>
            <th><?= Yii::t('orders/main', 'table.link') ?></th>
            <th><?= Yii::t('orders/main', 'table.quantity') ?></th>
            <th class="dropdown-th">
                <?= $this->render(
                    'layouts/services_dropdown',
                    compact(
                        'services',
                        'statusId',
                        'serviceId',
                        'modeId',
                        'search',
                        'searchType',
                        'searchTypes'
                    )
                ) ?>
            </th>
            <th><?= Yii::t('orders/main', 'table.status') ?></th>
            <th class="dropdown-th">
                <?= $this->render(
                    'layouts/modes_dropdown',
                    compact(
                        'modes',
                        'statusId',
                        'serviceId',
                        'modeId',
                        'search',
                        'searchType',
                        'searchTypes'
                    )
                ) ?>
            </th>
            <th><?= Yii::t('orders/main', 'table.created') ?></th>
        </tr>
        </thead>
        <tbody>
        <?= $this->render('layouts/orders_list', compact('orders')) ?>
        </tbody>
    </table>
<?= $this->render(
    'layouts/pagination',
    compact(
        'orders',
        'services',
        'statusId',
        'serviceId',
        'modeId',
        'search',
        'searchType',
        'searchTypes'
    )
) ?>