<?php

use yii\helpers\Html;

/**
 * @var $button array of button settings
 * @var $dropdownAttributes array of attributes ul html element
 * @var $items array of dropdown items
 */
?>

<div class="dropdown">
    <?= Html::button($button['title'] . ' <span class="caret"></span>', $button); ?>
    <?= Html::ul($items, $dropdownAttributes); ?>
</div>
