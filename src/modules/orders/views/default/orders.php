<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;

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
    <?php foreach ($statuses as $statusObject): ?>
        <li class="<?=$status===$statusObject->id?'active':''?>"><a href="<?=Url::toRoute(['/orders', 'status' => $statusObject->id, 'service'=>$service_id, 'mode'=>$mode, 'search'=>$search, 'search-type'=>$searchType])?>"><?=$statusObject->title;?></a></li>
    <?php endforeach; ?>
    <li class="pull-right custom-search">
        <form class="form-inline" action="/orders" method="get">
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
    array_walk($errors, function($errorBlock){
        foreach ($errorBlock as $error) {
            echo '<p>' . implode('</p><p>', $error) . '</p>';
        }
    });
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
                    <li class="<?=is_null($service_id)?'active':''?>"><a href="<?=Url::toRoute(['/orders', 'status' => $status, 'mode'=>$mode, 'search'=>$search, 'search-type'=>$searchType])?>">All ()</a></li>
                    <?php foreach ($services as $service) : ?>
                    <li class="<?=$service_id==$service->id?'active':''?>"><a href="<?=Url::toRoute(['/orders', 'status' => $status, 'service'=>$service->id, 'mode'=>$mode, 'search'=>$search, 'search-type'=>$searchType])?>"><span class="label-id"><?=$service->counts?></span>  <?=$service->name?></a></li>
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
                    <li class="<?=is_null($mode)?'active':''?>"><a href="<?=Url::toRoute(['/orders', 'status' => $status, 'service'=>$service_id, 'search'=>$search, 'search-type'=>$searchType])?>">All</a></li>
                    <?php foreach ($modes as $modeObject) : ?>
                        <li class="<?=$mode===$modeObject->id?'active':''?>"><a href="<?=Url::toRoute(['/orders', 'status' => $status, 'service'=>$service_id, 'mode'=>$modeObject->id, 'search'=>$search, 'search-type'=>$searchType])?>"><?=$modeObject->title?></a></li>
                    <?php endforeach; ?>
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
    <div class="col-sm-12">
        <?php
        echo Html::a('Save result','/orders/default/download', [
            'class' => 'btn btn-primary pull-right',
            'title' => Yii::t('yii', 'Save result'),
            'onclick'=>"
                $.ajax({
                    type:'POST',
                    cache: false,
                    url: '/orders/default/download',
                    dataType: 'json',
                    data: " . json_encode([
                            Yii::$app->request->csrfParam => Yii::$app->request->getCsrfToken(),
                            'status' => $status,
                            'service' => $service_id,
                            'mode' => $mode,
                            'search'=>$search,
                            'search-type'=>$searchType
                        ]) . ",
                    success  : function(response) {
                        console.log(response);
                    }
                });
                return false;",
        ]);
        ?>
    </div>

</div>
<!--
<pre>
<?=print_r($orders);?>
</pre>
-->
