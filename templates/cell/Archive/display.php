<?php
/**
 * @var \App\View\AppView $this
 * @var object $block
 * @var mixed $days
 */
?>
<div class="card mb-2">
    <h5 class="card-header">
        <?= $block->title ?>
    </h5>
    <div class="card-body px-1 py-0 mb-2">
        <?= $this->Calendar->render($days) ?>
    </div>
</div>