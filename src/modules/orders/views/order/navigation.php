<?php

use orders\helpers\TranslateHelper;
use orders\widgets\LiList;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $search : search query
 * @var $searchType : Type of search
 * @var $status_id : selected status
 * @var $statuses : Array of StatusesSearch
 * @var $search_types : Array of search types
 */

?>
<ul class="nav nav-tabs p-b">
    <li class="<?= is_null($status_id) ? 'active' : '' ?>"><a
                href="<?= Url::toRoute(
                    ['/orders', 'search' => $search, 'searchType' => $searchType]
                ) ?>"><?= TranslateHelper::t('main', 'global.all-orders') ?></a>
    </li>

    <?= LiList::widget(
        [
            'items' => $statuses,
            'valueField' => 'id',
            'labelField' => 'title',
            'selection' => $status_id,
            'url' => function ($object) use ($search, $searchType) {
                return Url::toRoute(
                    ['/orders', 'status_id' => $object->id, 'search' => $search, 'searchType' => $searchType]
                );
            }
        ]
    ) ?>

    <li class="pull-right custom-search">
        <form class="form-inline" action="/orders" method="get">
            <div class="input-group">
                <input type="hidden" name="status_id" value="<?= $status_id ?>">
                <input type="text" name="search" class="form-control" value="<?= $search ?>"
                       placeholder="Search orders">
                <span class="input-group-btn search-select-wrap">
                <?= Html::dropDownList(
                    'searchType',
                    $searchType,
                    ArrayHelper::map($search_types, 'id', 'title'),
                    ['class' => 'form-control search-select']
                ) ?>
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"
                                                                aria-hidden="true"></span></button>
            </span>
            </div>
        </form>
    </li>
</ul>

