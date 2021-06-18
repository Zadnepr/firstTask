<?php


namespace orders\widgets;

use yii\base\Widget;

/**
 * Widget for rendering errors in module orders
 */
class ErrorsWidget extends Widget
{
    public $errors;
    public $exception;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->errors || $this->exception) {
            echo $this->render('errors', ['errors' => $this->errors, 'exception' => $this->exception]);
        }
    }
}