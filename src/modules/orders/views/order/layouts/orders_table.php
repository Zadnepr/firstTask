<?php
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
 */

use orders\widgets\ErrorsWidget;

?>
<?php
if ($orders && $orders->getTotalCount() > 0) : ?>
    <table class="table order-table">
        <thead>
        <tr>
            <th><?= Yii::t('orders/main', 'table.id') ?></th>
            <th><?= Yii::t('orders/main', 'table.user') ?></th>
            <th><?= Yii::t('orders/main', 'table.link') ?></th>
            <th><?= Yii::t('orders/main', 'table.quantity') ?></th>
            <th class="dropdown-th">
                <?= $this->render(
                    'services_dropdown',
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
                    'modes_dropdown',
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
        <?= $this->render('orders_list', compact('orders')) ?>
        </tbody>
    </table>
<?php
elseif ($orders && $orders->getTotalCount() == 0): ?>
    <?= ErrorsWidget::widget(['errors' => [Yii::t('orders/main', 'error.nullResult')]]) ?>
<?php
endif; ?>
