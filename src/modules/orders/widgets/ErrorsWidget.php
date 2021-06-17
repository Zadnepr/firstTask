<?php


namespace orders\widgets;

use yii\base\Widget;

/**
 * Widget for rendering errors in module orders
 */
class ErrorsWidget extends Widget
{
    public $errors;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->errors) {
            $this->render('errors', ['errors' => $this->errors]);
        }
    }
}