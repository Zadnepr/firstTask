<?php

/**
 * @var $errors : Validation errors array
 */

?>
<div class="alert alert-danger" role="alert">
    <?php
    foreach ($errors as $error): ?>
        <p><?= is_array($error) ? implode('</p><p>', $error) : $error ?></p>
    <?php
    endforeach; ?>
</div>