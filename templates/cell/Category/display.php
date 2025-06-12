<?php
/**
 * @var \App\View\AppView $this
 * @var object $block
 * @var mixed $categories
 */
?>
<div class="card mb-2">
    <h5 class="card-header">
        <?= $block->title ?>
    </h5>
    <div class="card-body p-1">
        <?= $this->element('categories', ['children' => $categories]) ?>
    </div>
</div>
