<?php


namespace orders\widgets;

use yii;
use yii\base\Widget;

/**
 * Widget for rendering dropdown li lists in module orders
 */
class BootstrapDropdownWidget extends Widget
{
    public $id = 'dropdownMenu1';
    public $attributes;
    public $button;
    public $items;
    public $selection;
    public $url;
    public $valueField;
    public $labelField;
    public $nullField = false;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        echo '<div class="dropdown">';
        if ($this->button) {
            echo <<<HTML
            <button class="btn btn-th btn-default dropdown-toggle" type="button" id="{$this->id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                {$this->button['title']}
                <span class="caret"></span>
            </button>
            HTML;
        }

        echo '<ul ' . implode(
                " ",
                array_map(
                    function ($key, $item) {
                        return "{$key}=\"{$item}\"";
                    },
                    array_keys($this->attributes),
                    $this->attributes
                )
            ) . '>';
        $value = $this->valueField ? $this->valueField : 'id';
        if ($this->nullField) {
            echo '<li class="' . ((is_null(
                    $this->selection
                )) ? 'active' : '') . '"><a href="' . $this->nullField['url'] . '">' . $this->nullField['title'] . '</a></li>';
        }
        if ($this->items) {
            foreach ($this->items as $item) {
                $label = $this->labelField ? self::doIfCallable($this->labelField, [$item]) : 'title';

                $url = self::doIfCallable($this->url, [$item]);
                echo "<li class='" . ($this->selection === $item->$value ? 'active' : '') . "'><a href='{$url}'>" . Yii::t(
                        'orders/main',
                        (property_exists($item, $label) ? $item->$label : $label)
                    ) . "</a></li>";
            }
        }
        echo '</ul>';
        echo '</div>';
    }

    /**
     * Returns string|null after check is_callable first param $checking or if first param is string return string value first param $checking
     * @param string|callable $checking
     * @param array|null $params
     * @return string|null|mixed
     */
    private function doIfCallable($checking, array $params = null)
    {
        return is_callable($checking) ? call_user_func_array($checking, $params) : (is_string(
            $checking
        ) ? $checking : null);
    }
}