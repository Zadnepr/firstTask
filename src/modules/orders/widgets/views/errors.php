<?php

/**
 * @var $errors : Validation errors array
 * @var $exception : Validation errors array
 */

?>
<div class="alert alert-danger" role="alert">
    <?php
    if ($exception):?>
        <p><?= $exception->getMessage() ?></p>
    <?php
    endif; ?>

    <?php
    foreach ($errors as $error): ?>
        <p><?= is_array($error) ? implode('</p><p>', $error) : $error ?></p>
    <?php
    endforeach; ?>
</div>