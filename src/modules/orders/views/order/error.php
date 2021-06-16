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
?>

<?= $this->render(
    '@orders/views/order/navigation',
    compact('status_id', 'statuses', 'search', 'searchType', 'search_types')
) ?>

<?= Errors::widget(['errors' => $errors]) ?>
