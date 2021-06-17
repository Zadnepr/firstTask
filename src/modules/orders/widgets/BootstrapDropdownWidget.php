<?php


namespace orders\widgets;

use yii;
use yii\base\Widget;
use yii\helpers\Html;

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
        $defaultSettings = [
            "class"=>"btn btn-th btn-default dropdown-toggle",
            "type"=>"button",
            "data-toggle"=>"dropdown",
            "aria-haspopup"=>"true",
            "aria-expanded"=>"true",
        ];
        $defaultDropdownAttributes = [
            'item' => function($item, $index) {
                return Html::tag(
                    'li',
                    $this->render('bootstrap_dropdown_item', ['item' => $item]),
                    ['class' => $item['active'] ? 'active' : '']
                );
            }
        ];

        $buttonSettings = array_merge($defaultSettings, $this->button);
        $dropdownAttributes = array_merge($defaultDropdownAttributes, $this->attributes);
        $items = [];

        if ($this->nullField) {
            $items[] = [
                'active' => is_null($this->selection) ? true : false,
                'url' => $this->nullField['url'],
                'title' => $this->nullField['title'],
            ];
        }

        if ($this->items) {
            $value = $this->valueField ? $this->valueField : 'id';
            foreach ($this->items as $item) {
                $label = $this->labelField ? self::doIfCallable($this->labelField, [$item]) : 'title';
                $items[] = [
                        'active' => ($this->selection === $item->$value ? true : false),
                        'url' => self::doIfCallable($this->url, [$item]),
                        'title' => Yii::t('orders/main',(property_exists($item, $label) ? $item->$label : $label)),
                    ];
            }
        }

        return $this->render('bootstrap_dropdown', [
            'button' => $buttonSettings,
            'dropdownAttributes' => $dropdownAttributes,
            'items' => $items,
        ]);
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