<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\modules\orders\widgets\Errors;
use app\modules\orders\widgets\LiList;

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
    <li class="<?=is_null($status)?'active':''?>"><a href="<?=Url::toRoute(['/orders'])?>">All orders</a></li>

    <?=LiList::widget(['items'=>$statuses, 'valueField'=>'id', 'labelField'=>'title', 'selection'=>$status, 'url'=>function($object) use($search, $searchType){
        return Url::toRoute(['/orders', 'status' => $object->id, 'search'=>$search, 'search-type'=>$searchType]);
    }])?>

    <li class="pull-right custom-search">
        <form class="form-inline" action="/orders" method="get">
            <div class="input-group">
                <input type="hidden" name="status" value="<?=$status?>">
                <input type="text" name="search" class="form-control" value="<?=$search?>" placeholder="Search orders">
                <span class="input-group-btn search-select-wrap">
                <?=Html::dropDownList('search-type', $searchType, ArrayHelper::map($search_types, 'id', 'title'), ['class'=>'form-control search-select' ])?>
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
        <th>ID</th>
        <th>User</th>
        <th>Link</th>
        <th>Quantity</th>
        <th class="dropdown-th">
            <div class="dropdown">
                <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Service
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li class="<?=is_null($service_id)?'active':''?>"><a href="<?=Url::toRoute(['/orders', 'status' => $status, 'mode'=>$mode, 'search'=>$search, 'search-type'=>$searchType])?>">All (<?=$services_sum?>)</a></li>
                    <?=LiList::widget(['items'=>$services, 'valueField'=>'id', 'labelField'=>function($object){
                        return '<span class="label-id">' . $object->counts . '</span> ' . $object->name;
                    }, 'selection'=>$service_id, 'url'=>function($object) use($status, $mode, $search, $searchType){
                        return Url::toRoute(['/orders', 'status' => $status, 'service'=>$object->id, 'mode'=>$mode, 'search'=>$search, 'search-type'=>$searchType]);
                    }])?>
                </ul>
            </div>
        </th>
        <th>Status</th>
        <th class="dropdown-th">
            <div class="dropdown">
                <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Mode
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li class="<?=is_null($mode)?'active':''?>"><a href="<?=Url::toRoute(['/orders', 'status' => $status, 'service'=>$service_id, 'search'=>$search, 'search-type'=>$searchType])?>">All</a></li>
                    <?=LiList::widget(['items'=>$modes, 'selection'=>$mode, 'url'=>function($object) use($status, $mode, $service_id, $search, $searchType){
                        return Url::toRoute(['/orders', 'status' => $status, 'service'=>$service_id, 'mode'=>$object->id, 'search'=>$search, 'search-type'=>$searchType]);
                    }])?>
                </ul>
            </div>
        </th>
        <th>Created</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
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
            <?=LinkPager::widget(['pagination' => $pages])?>
        </nav>
    </div>
    <div class="col-sm-4 pagination-counters">
        <?=$offset?> to <?=$total?> of <?=$totalCount?>
    </div>
    <div class="col-sm-12">
        <?php
        echo Html::a('Save result',Url::toRoute(['/orders', 'status' => $status, 'service'=>$service_id, 'mode'=>$mode, 'search'=>$search, 'search-type'=>$searchType, 'download'=>1]), [
            'class' => 'btn btn-primary pull-right',
            'title' => Yii::t('yii', 'Save result'),
            'download' => 'order.csv'
        ]);
        ?>
    </div>
</div>