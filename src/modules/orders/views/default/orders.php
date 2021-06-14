<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\modules\orders\widgets\Errors;
use app\modules\orders\widgets\LiList;
use app\modules\orders\helpers\ServicesCounts;
use app\modules\orders\Module;

/**
 * @var $search: search query
 * @var $searchType: Type of search
 * @var $status_id: selected status
 * @var $service_id: selected service
 * @var $mode_id: selected mode
 * @var $orders: Object ActiveDataProvider with Orders list
 * @var $services: Object ActiveDataProvider with Services list
 * @var $statuses: Array of Statuses
 * @var $modes: Array of Modes
 * @var $search_types: Array of search types
 * @var $errors: Validation Errors
 */

$this->registerJsFile(
    '/modules/orders/views/js/jquery.min.js',
);
$this->registerJsFile(
    '/modules/orders/views/js/bootstrap.min.js',
);
$this->registerCssFile(
    '/modules/orders/views/css/bootstrap.min.css',
);
$this->registerCssFile(
    '/modules/orders/views/css/custom.css',
);
?>
<ul class="nav nav-tabs p-b">
    <li class="<?=is_null($status_id)?'active':''?>"><a href="<?=Url::toRoute(['/orders', 'search'=>$search, 'searchType'=>$searchType])?>"><?=Module::t('main', 'global.all-orders')?></a></li>

    <?=LiList::widget(['items'=>$statuses, 'valueField'=>'id', 'labelField'=>'title', 'selection'=>$status_id, 'url'=>function($object) use($search, $searchType){
        return Url::toRoute(['/orders', 'status_id' => $object->id, 'search'=>$search, 'searchType'=>$searchType]);
    }])?>

    <li class="pull-right custom-search">
        <form class="form-inline" action="/orders" method="get">
            <div class="input-group">
                <input type="hidden" name="status_id" value="<?=$status_id?>">
                <input type="text" name="search" class="form-control" value="<?=$search?>" placeholder="Search orders">
                <span class="input-group-btn search-select-wrap">
                <?=Html::dropDownList('searchType', $searchType, ArrayHelper::map($search_types, 'id', 'title'), ['class'=>'form-control search-select' ])?>
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
            </span>
            </div>
        </form>
    </li>
</ul>

<?=Errors::widget(['errors'=>$errors])?>

<table class="table order-table">
    <thead>
    <tr>
        <th><?=Module::t('main', 'table.id')?></th>
        <th><?=Module::t('main', 'table.user')?></th>
        <th><?=Module::t('main', 'table.link')?></th>
        <th><?=Module::t('main', 'table.quantity')?></th>
        <th class="dropdown-th">
            <div class="dropdown">
                <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <?=Module::t('main', 'table.service')?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li class="<?=is_null($service_id)?'active':''?>"><a href="<?=Url::toRoute(['/orders', 'status_id' => $status_id, 'mode_id'=>$mode_id, 'search'=>$search, 'searchType'=>$searchType])?>"><?=Module::t('main', 'services.all.count', ServicesCounts::count($services))?></a></li>
                    <?=LiList::widget(['items'=>$services->getModels(), 'valueField'=>'id', 'labelField'=>function($object){
                        return '<span class="label-id">' . $object->counts . '</span> ' . $object->name;
                    }, 'selection'=>$service_id, 'url'=>function($object) use($status_id, $mode_id, $search, $searchType){
                        return Url::toRoute(['/orders', 'status_id' => $status_id, 'service_id'=>$object->id, 'mode_id'=>$mode_id, 'search'=>$search, 'searchType'=>$searchType]);
                    }])?>
                </ul>
            </div>
        </th>
        <th><?=Module::t('main', 'table.status')?></th>
        <th class="dropdown-th">
            <div class="dropdown">
                <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <?=Module::t('main', 'table.mode')?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li class="<?=is_null($mode_id)?'active':''?>"><a href="<?=Url::toRoute(['/orders', 'status_id' => $status_id, 'service_id'=>$service_id, 'search'=>$search, 'searchType'=>$searchType])?>"><?=Module::t('main', 'All')?></a></li>
                    <?=LiList::widget(['items'=>$modes, 'selection'=>$mode_id, 'url'=>function($object) use($status_id, $mode_id, $service_id, $search, $searchType){
                        return Url::toRoute(['/orders', 'status_id' => $status_id, 'service_id'=>$service_id, 'mode_id'=>$object->id, 'search'=>$search, 'searchType'=>$searchType]);
                    }])?>
                </ul>
            </div>
        </th>
        <th><?=Module::t('main', 'table.created')?></th>
    </tr>
    </thead>
    <tbody>
        <?php if($orders) foreach ($orders->getModels() as $order): ?>
        <tr>
            <td><?=$order->id?></td>
            <td><?=$order->username;?></td>
            <td class="link"><?=$order->link?></td>
            <td><?=$order->quantity?></td>
            <td class="service">
                <span class="label-id"><?=$order->services->id?></span><?=$order->service_title?>
            </td>
            <td><?=$order->status_title;?></td>
            <td><?=$order->mode_title;?></td>
            <td><span class="nowrap"><?=$order->date;?></span><span class="nowrap"><?=$order->time;?></span></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="row">
    <div class="col-sm-8">
        <nav>
            <?=LinkPager::widget(['pagination' => $orders->getPagination()])?>
        </nav>
    </div>
    <div class="col-sm-4 pagination-counters">
        <?=\app\modules\orders\widgets\PaginationCounters::widget(['orders'=>$orders])?>
    </div>
    <div class="col-sm-12">
        <?php
        echo Html::a(Module::t('main', 'global.save-results'), Url::toRoute(['/orders', 'status_id' => $status_id, 'service_id'=>$service_id, 'mode_id'=>$mode_id, 'search'=>$search, 'searchType'=>$searchType, 'download'=>1]), [
            'class' => 'btn btn-primary pull-right',
            'title' => Module::t('main', 'global.save-results'),
        ]);
        ?>
    </div>
</div>