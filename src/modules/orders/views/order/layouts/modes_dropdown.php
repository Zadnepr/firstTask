<?php

use orders\widgets\BootstrapDropdownWidget;
use yii\helpers\Url;

/**
 * @var $search : search query
 * @var $searchType : Type of search
 * @var $statusId : selected status
 * @var $serviceId : selected service
 * @var $modeId : selected mode
 * @var $modes : Object ActiveDataProvider with Services list
 */

?>
<?= BootstrapDropdownWidget::widget(
    [
        'attributes' => ['class'=>'dropdown-menu', 'aria-labelledby'=>'dropdownMenu1'],
        'button' => ['title'=>Yii::t('orders/main', 'table.mode')],
        'items' => $modes,
        'selection' => $modeId,
        'url' => function ($object) use ($statusId, $modeId, $serviceId, $search, $searchType) {
            return Url::toRoute(
                [
                    '/orders',
                    'status_id' => $statusId,
                    'service_id' => $serviceId,
                    'mode_id' => $object->id,
                    'search' => $search,
                    'searchType' => $searchType
                ]
            );
        },
        'nullField' => [
            'url' => Url::toRoute(
                [
                    '/orders',
                    'status_id' => $statusId,
                    'service_id' => $serviceId,
                    'search' => $search,
                    'searchType' => $searchType
                ]
            ),
            'title' => Yii::t('orders/main', 'mode.all'),
        ]
    ]
) ?>
