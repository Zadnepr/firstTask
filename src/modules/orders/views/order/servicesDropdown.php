<?php

use orders\helpers\ServicesCountsHelper;
use orders\helpers\TranslateHelper;
use orders\widgets\LiList;
use yii\helpers\Url;

/**
 * @var $search : search query
 * @var $searchType : Type of search
 * @var $status_id : selected status
 * @var $service_id : selected service
 * @var $mode_id : selected mode
 * @var $services : Object ActiveDataProvider with Services list
 * @var $search_types : Array of search types
 */

?>
<div class="dropdown">
    <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <?= TranslateHelper::t('main', 'table.service') ?>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li class="<?= is_null($service_id) ? 'active' : '' ?>"><a
                    href="<?= Url::toRoute(
                        [
                            '/orders',
                            'status_id' => $status_id,
                            'mode_id' => $mode_id,
                            'search' => $search,
                            'searchType' => $searchType
                        ]
                    ) ?>"><?= TranslateHelper::t('main', 'services.all.count', ServicesCountsHelper::count($services)) ?></a>
        </li>
        <?= LiList::widget(
            [
                'items' => $services->getModels(),
                'valueField' => 'id',
                'labelField' => function ($object) {
                    return '<span class="label-id">' . $object->counts . '</span> ' . $object->name;
                },
                'selection' => $service_id,
                'url' => function ($object) use ($status_id, $mode_id, $search, $searchType) {
                    return Url::toRoute(
                        [
                            '/orders',
                            'status_id' => $status_id,
                            'service_id' => $object->id,
                            'mode_id' => $mode_id,
                            'search' => $search,
                            'searchType' => $searchType
                        ]
                    );
                }
            ]
        ) ?>
    </ul>
</div>
