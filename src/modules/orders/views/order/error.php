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
<?php
var_dump($this); ?>
<?= ErrorsWidget::widget(['errors' => $errors]) ?>