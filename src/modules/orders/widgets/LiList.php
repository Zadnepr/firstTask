<?php


namespace app\modules\orders\widgets;

use Yii;
use yii\base\Widget;
use app\modules\orders\Module;

class LiList extends Widget
{
    public $items;
    public $selection;
    public $url;
    public $valueField;
    public $labelField;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $value = $this->valueField ? $this->valueField : 'id';

        if($this->items){
            foreach ($this->items as $item) {
                $label = $this->labelField ? self::checkCallable($this->labelField, [$item]) : 'title';

                $url = self::checkCallable($this->url, [$item]);
                echo "<li class='" . ($this->selection===$item->$value ? 'active' : '') . "'><a href='{$url}'>" . Module::t('main', (property_exists($item, $label) ? $item->$label : $label)) . "</a></li>";
            }
        }
    }

    function checkCallable($checking, $params){
        return is_callable($checking) ? call_user_func_array($checking, $params) : $checking;
    }
}