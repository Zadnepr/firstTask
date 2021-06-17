<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $search : search query
 * @var $searchType : Type of search
 * @var $statusId : selected status
 * @var $statuses : Array of StatusesSearch
 * @var $searchTypes : Array of search types
 */

?>
<ul class="nav nav-tabs p-b">
    <?php
    if ($statuses): ?>
        <li class="<?= ((is_null($statusId)) ? 'active' : '') ?>">
            <a href="<?= Url::toRoute(['/orders', 'search' => $search, 'searchType' => $searchType]) ?>"><?= Yii::t(
                    'orders/main',
                    'global.all-orders'
                ) ?></a>
        </li>
        <?php
        foreach ($statuses as $status): ?>
            <li class="<?= (($statusId === $status->id) ? 'active' : '') ?>">
                <a href="<?= Url::toRoute(
                    ['/orders', 'statusId' => $status->id, 'search' => $search, 'searchType' => $searchType]
                ) ?>"><?= Yii::t(
                        'orders/main',
                        $status->title
                    ) ?></a>
            </li>
        <?php
        endforeach; ?>
    <?php
    endif; ?>
    <li class="pull-right custom-search">
        <form class="form-inline"
              action="<?= Url::toRoute(['/orders']) ?>" method="get">
            <div class="input-group">
                <input type="hidden" name="statusId" value="<?= $statusId ?>">
                <input type="text" name="search" class="form-control" value="<?= $search ?>"
                       placeholder="Search orders">
                <span class="input-group-btn search-select-wrap">
                <?= Html::dropDownList(
                    'searchType',
                    $searchType,
                    ArrayHelper::map($searchTypes, 'id', 'title'),
                    ['class' => 'form-control search-select']
                ) ?>
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"
                                                                aria-hidden="true"></span></button>
            </span>
            </div>
        </form>
    </li>
</ul>

