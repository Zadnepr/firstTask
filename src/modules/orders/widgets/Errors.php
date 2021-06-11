<?php


namespace app\modules\orders\widgets;

use Yii;
use yii\base\Widget;

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

    public function run()
    {
        if($this->errors){
            echo '<div class="alert alert-danger" role="alert">';
            array_walk($this->errors, function($errorBlock){
                foreach ($errorBlock as $error) {
                    echo '<p>' . implode('</p><p>', $error) . '</p>';
                }
            });
            echo '</div>';
        }
    }
}