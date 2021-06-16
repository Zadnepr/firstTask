<?php


namespace orders\widgets;

use yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;


/**
 * Widget for rendering pagination counters (right side) in module orders
 */
class PaginationCountersWidget extends Widget
{
    public ActiveDataProvider $orders;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->orders) {
            if ($this->orders->getTotalCount() < $this->orders->getPagination()->getPageSize()) {
                echo Yii::t('orders/main', "global.pagination.counter.total", $this->orders->getTotalCount());
            } else {
                $start = $this->orders->getPagination()->getOffset() + 1;
                $end = $this->orders->getPagination()->getOffset() + $this->orders->getCount();
                $total = $this->orders->getTotalCount();
                echo Yii::t('orders/main', "global.pagination.counters", [$start, $end, $total]);
            }
        }
    }
}