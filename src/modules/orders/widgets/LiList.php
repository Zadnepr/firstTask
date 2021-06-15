<?php


namespace orders\widgets;

use orders\helpers\TranslateHelper;
use yii\base\Widget;

/**
 * Widget for rendering dropdown li lists in module orders
 */
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

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $value = $this->valueField ? $this->valueField : 'id';

        if ($this->items) {
            foreach ($this->items as $item) {
                $label = $this->labelField ? self::checkCallable($this->labelField, [$item]) : 'title';

                $url = self::checkCallable($this->url, [$item]);
                echo "<li class='" . ($this->selection === $item->$value ? 'active' : '') . "'><a href='{$url}'>" . TranslateHelper::t(
                        'main',
                        (property_exists($item, $label) ? $item->$label : $label)
                    ) . "</a></li>";
            }
        }
    }

    /**
     * Check is_callable
     * @param string|callable $checking
     * @param array|null $params
     * @return false|mixed
     */
    function checkCallable($checking, array $params = null)
    {
        return is_callable($checking) ? call_user_func_array($checking, $params) : $checking;
    }
}