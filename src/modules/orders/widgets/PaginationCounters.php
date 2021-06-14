<?php


namespace app\modules\orders\widgets;

use Yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use app\modules\orders\Module;


class PaginationCounters extends Widget
{
    public ActiveDataProvider $orders;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        if($this->orders){
            if($this->orders->getTotalCount() <  $this->orders->getPagination()->getPageSize()){
                echo Module::t('main',"global.pagination.counter.total", $this->orders->getTotalCount());
            }
            else {
                $start = $this->orders->getPagination()->getOffset()+1;
                $end = $this->orders->getPagination()->getOffset()+$this->orders->getCount();
                $total = $this->orders->getTotalCount();
                echo Module::t('main',"global.pagination.counters", [$start, $end, $total]);
            }
        }
    }
}