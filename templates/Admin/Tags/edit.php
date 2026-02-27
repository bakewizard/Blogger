<?php
/**
 * @var \App\View\AppView $this
 * @var array $config
 * @var \Blogger\Model\Entity\Tag $tag
 */
?>
<div class="card card-success card-outline">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-edit me-2"></i><?= __('Edit Tag') ?></div>
        <?php if (count($config['App']['I18n']['languages']) > 1) : ?>
            <div class="card-tools">
                <?= $this->element('form/locales', ['locale' => $tag->_locale]) ?>
            </div>
        <?php endif; ?>
    </div>
    <?= $this->Form->create($tag, ['align' => 'horizontal']) ?>
    <div class="card-body">
        <?= $this->Form->control('title'); ?>
        <?= $this->Form->control('alias'); ?>
    </div>
    <div class="card-footer">
        <?= $this->element('form/save_buttons') ?>
        <?= $this->Html->link('<i class="fa-solid fa-times-circle"></i> ' . __('Cancel'), ['action' => 'index', '?' => $this->request->getQueryParams()], ['class' => 'btn btn-outline-danger', 'escape' => false]) ?>
    </div>
    <?= $this->Form->end() ?>
</div>