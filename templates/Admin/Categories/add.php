<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $parentCategories
 * @var \Blogger\Model\Entity\Category $category
 */
?>
<?= $this->Html->script(['/backend/plugins/tinymce/tinymce.min', 'Blogger.backend/categories/category'], ['block' => true]); ?>
<div class="card card-success card-outline">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-edit me-2"></i><?= __('Add Category') ?></div>
    </div>
    <?= $this->Form->create($category, ['align' => 'horizontal', 'type' => 'file', 'id' => 'categories-add-form']) ?>
    <div class="card-body">
        <?= $this->Form->control('parent_id', ['options' => $parentCategories, 'empty' => 'No parent category']); ?>
        <?= $this->Form->control('name'); ?>
        <?= $this->Form->control('alias'); ?>
        <?= $this->Form->control('description'); ?>
        <?=
        $this->Form->control('seo_title', [
            'append' => $this->Form->button('<i class="fa-solid fa-sync-alt"></i>', [
                'type' => 'button',
                'class' => 'btn btn-secondary',
                'id' => 'seo-title-refresh',
                'title' => __('Copy from category name'),
                'escapeTitle' => false
            ])
        ]);
        ?>
        <?=
        $this->Form->control('seo_description', [
            'append' => $this->Form->button('<i class="fa-solid fa-sync-alt"></i>', [
                'type' => 'button',
                'class' => 'btn btn-secondary',
                'id' => 'seo-description-refresh',
                'title' => __('Copy from category description'),
                'escapeTitle' => false
            ])
        ]);
        ?>
        <?= $this->Form->control('seo_keywords'); ?>
        <?= $this->Form->control('enabled', ['switch' => true]); ?>
    </div>
    <div class="card-footer">
        <?= $this->element('form/save_buttons') ?>
        <?= $this->Html->link('<i class="fa-solid fa-times-circle"></i> ' . __('Cancel'), ['action' => 'index', '?' => $this->request->getQueryParams()], ['class' => 'btn btn-outline-danger', 'escape' => false]) ?>
    </div>
    <?= $this->Form->end() ?>
</div>