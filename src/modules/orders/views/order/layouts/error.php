<?php

use orders\widgets\ErrorsWidget;

/**
 * @var $search : search query
 * @var $searchType : Type of search
 * @var $statusId : selected status
 * @var $statuses : Array of StatusesSearch
 * @var $searchTypes : Array of search types
 * @var $errors : Validation ErrorsWidget
 */
?>

<?= $this->render(
    '@orders/views/order/navigation',
    compact('statusId', 'statuses', 'search', 'searchType', 'searchTypes')
) ?>

<?= ErrorsWidget::widget(['errors' => $errors]) ?>
