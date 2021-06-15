<?php

use orders\helpers\TranslateHelper;
use orders\widgets\LiList;
use yii\helpers\Url;

/**
 * @var $search : search query
 * @var $searchType : Type of search
 * @var $status_id : selected status
 * @var $service_id : selected service
 * @var $mode_id : selected mode
 * @var $modes : Object ActiveDataProvider with Services list
 * @var $search_types : Array of search types
 */

?>
<div class="dropdown">
    <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <?= TranslateHelper::t('main', 'table.mode') ?>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li class="<?= is_null($mode_id) ? 'active' : '' ?>"><a
                    href="<?= Url::toRoute(
                        [
                            '/orders',
                            'status_id' => $status_id,
                            'service_id' => $service_id,
                            'search' => $search,
                            'searchType' => $searchType
                        ]
                    ) ?>"><?= TranslateHelper::t('main', 'All') ?></a>
        </li>
        <?= LiList::widget(
            [
                'items' => $modes,
                'selection' => $mode_id,
                'url' => function ($object) use ($status_id, $mode_id, $service_id, $search, $searchType) {
                    return Url::toRoute(
                        [
                            '/orders',
                            'status_id' => $status_id,
                            'service_id' => $service_id,
                            'mode_id' => $object->id,
                            'search' => $search,
                            'searchType' => $searchType
                        ]
                    );
                }
            ]
        ) ?>
    </ul>
</div>
