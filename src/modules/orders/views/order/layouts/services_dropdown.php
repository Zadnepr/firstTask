<?php

use orders\helpers\ServicesCountsHelper;
use orders\widgets\BootstrapDropdownWidget;
use yii\helpers\Url;

/**
 * @var $search : search query
 * @var $searchType : Type of search
 * @var $statusId : selected status
 * @var $serviceId : selected service
 * @var $modeId : selected mode
 * @var $services : Object ActiveDataProvider with Services list
 */

?>
<?= BootstrapDropdownWidget::widget(
    [
        'attributes' => ['class' => 'dropdown-menu', 'aria-labelledby' => 'dropdownMenu1'],
        'button' => ['title' => Yii::t('orders/main', 'table.service')],
        'items' => $services->getModels(),
        'valueField' => 'id',
        'labelField' => function ($object) {
            return '<span class="label-id">' . $object->counts . '</span> ' . $object->name;
        },
        'selection' => $serviceId,
        'url' => function ($object) use ($statusId, $modeId, $search, $searchType) {
            return Url::toRoute(
                [
                    '/orders',
                    'statusId' => $statusId,
                    'serviceId' => $object->id,
                    'modeId' => $modeId,
                    'search' => $search ? $search : null,
                    'searchType' => $search ? $searchType : null
                ]
            );
        },
        'nullField' => [
            'url' => Url::toRoute(
                [
                    '/orders',
                    'statusId' => $statusId,
                    'modeId' => $modeId,
                    'search' => $search ? $search : null,
                    'searchType' => $search ? $searchType : null
                ]
            ),
            'title' => Yii::t('orders/main', 'services.all.count', ServicesCountsHelper::count($services)),
        ]
    ]
) ?>
