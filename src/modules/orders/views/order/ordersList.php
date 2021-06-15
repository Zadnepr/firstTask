<?php

/**
 * @var $orders : Object ActiveDataProvider with Orders list
 */

if ($orders) {
    foreach ($orders->getModels() as $order): ?>
        <tr>
            <td><?= $order->id ?></td>
            <td><?= $order->username; ?></td>
            <td class="link"><?= $order->link ?></td>
            <td><?= $order->quantity ?></td>
            <td class="service">
                <span class="label-id"><?= $order->services->id ?></span><?= $order->service_title ?>
            </td>
            <td><?= $order->status_title; ?></td>
            <td><?= $order->mode_title; ?></td>
            <td><span class="nowrap"><?= $order->date; ?></span><span class="nowrap"><?= $order->time; ?></span></td>
        </tr>
    <?php
    endforeach;
} ?>