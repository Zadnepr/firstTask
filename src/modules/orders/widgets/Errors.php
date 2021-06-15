<?php


namespace orders\widgets;

use yii\base\Widget;

/**
 * Widget for rendering errors in module orders
 */
class Errors extends Widget
{
    public $errors;

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
        if ($this->errors) {
            echo '<div class="alert alert-danger" role="alert">';
            if ($this->errors) {
                foreach ($this->errors as $error) {
                    echo '<p>' . implode('</p><p>', $error) . '</p>';
                }
            }
            echo '</div>';
        }
    }
}