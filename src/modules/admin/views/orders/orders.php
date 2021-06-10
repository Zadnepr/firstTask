<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;

$this->registerJsFile(
    '/modules/admin/views/js/jquery.min.js',
);
$this->registerJsFile(
    '/modules/admin/views/js/bootstrap.min.js',
);
$this->registerCssFile(
    '/modules/admin/views/css/bootstrap.min.css',
);
$this->registerCssFile(
    '/modules/admin/views/css/custom.css',
);
?>
<ul class="nav nav-tabs p-b">
    <li class="<?=is_null($status)?'active':''?>"><a href="<?=Url::toRoute(['/admin/orders'])?>">All orders</a></li>
    <li class="<?=$status==='0'?'active':''?>"><a href="<?=Url::toRoute(['/admin/orders', 'status' => '0'])?>">Pending</a></li>
    <li class="<?=$status==='1'?'active':''?>"><a href="<?=Url::toRoute(['/admin/orders', 'status' => '1'])?>">In progress</a></li>
    <li class="<?=$status==='2'?'active':''?>"><a href="<?=Url::toRoute(['/admin/orders', 'status' => '2'])?>">Completed</a></li>
    <li class="<?=$status==='3'?'active':''?>"><a href="<?=Url::toRoute(['/admin/orders', 'status' => '3'])?>">Canceled</a></li>
    <li class="<?=$status==='4'?'active':''?>"><a href="<?=Url::toRoute(['/admin/orders', 'status' => '4'])?>">Error</a></li>
    <li class="pull-right custom-search">
        <form class="form-inline" action="/admin/orders" method="get">
            <div class="input-group">
                <input type="hidden" name="status" value="<?=$status?>">
                <input type="text" name="search" class="form-control" value="<?=$search?>" placeholder="Search orders">
                <span class="input-group-btn search-select-wrap">
            <select class="form-control search-select" name="search-type">
              <option value="1" <?=$searchType==='1'?'selected':''?>>Order ID</option>
              <option value="2" <?=$searchType==='2'?'selected':''?>>Link</option>
              <option value="3" <?=$searchType==='3'?'selected':''?>>Username</option>
            </select>
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
            </span>
            </div>
        </form>
    </li>
</ul>

<?php
if($errors){
    echo '<div class="alert alert-danger" role="alert">';
    foreach ($errors as $error) {
        echo '<p>' . implode('</p><p>', $error) . '</p>';
    }
    echo '</div>';
}
?>
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
                    <li class="active"><a href="<?=Url::toRoute(['/admin/orders', 'status' => $status, 'search'=>$search, 'search-type'=>$searchType])?>">All (<?=$totalCount?>)</a></li>
                    <?php foreach ($services as $service) : ?>
                    <li><a href="<?=Url::toRoute(['/admin/orders', 'status' => $status, 'service'=>$service->id, 'search'=>$search, 'search-type'=>$searchType])?>"><span class="label-id"><?=$service->counts?></span>  <?=$service->name?></a></li>
                    <?php endforeach; ?>
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
                    <li class="active"><a href="">All</a></li>
                    <li><a href="">Manual</a></li>
                    <li><a href="">Auto</a></li>
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
            <td><?=trim($order->users->first_name . ' ' . $order->users->last_name)?></td>
            <td class="link"><?=$order->link?></td>
            <td><?=$order->quantity?></td>
            <td class="service">
                <span class="label-id"><?=$order->services->id?></span><?=$order->services->name?>
            </td>
            <td><?=$order->getStatusTitle()?></td>
            <td><?=$order->getModeTitle()?></td>
            <td><span class="nowrap"><?=$order->getDate()?></span><span class="nowrap"><?=$order->getTime()?></span></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="row">
    <div class="col-sm-8">
        <nav>
            <?php
            echo LinkPager::widget([
                'pagination' => $pages,
            ]);
            ?>
        </nav>
    </div>
    <div class="col-sm-4 pagination-counters">
        <?=$offset?> to <?=$total?> of <?=$totalCount?>
    </div>

</div>
<!--
<pre>
<?=print_r($orders);?>
</pre>
-->
