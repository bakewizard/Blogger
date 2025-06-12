<?php
/**
 * @var \App\View\AppView $this
 * @var object $block
 * @var mixed $url
 */
?>
<div class="card mb-2">
    <h5 class="card-header">
        <?= $block->title ?>
    </h5>
    <div class="card-body p-2">
        <?= $this->Form->create(null, ['type' => 'get', 'url' => $url, 'id' => 'search-form']); ?>
        <div class="input-group w-100">
            <input type="text" class="form-control" id="search-field" placeholder="<?= __d('blogger', 'Search') ?>..." autocomplete="off" name="text">
            <button class="btn btn-primary text-light" type="submit" id="search-button">
                <i class="bi bi-search"></i>
            </button>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>