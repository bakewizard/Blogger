<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $categories
 * @var array $config
 * @var mixed $tags
 * @var mixed $users
 * @var \Blogger\Model\Entity\Article $article
 */
?>
<?= $this->Html->script(['/backend/plugins/tinymce/tinymce.min', 'Blogger.backend/articles/article'], ['block' => true, 'type' => 'module']) ?>
<div class="card card-success card-outline">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-edit me-2"></i><?= __('Edit an article') ?></div>
        <?php if (count($config['App']['I18n']['languages']) > 1): ?>
            <div class="card-tools">
                <?= $this->element('form/locales', ['locale' => $article->_locale]) ?>
            </div>
        <?php endif; ?>
    </div>
    <?= $this->Form->create($article, ['align' => 'horizontal']) ?>
    <div class="card-body">
        <?= $this->Form->control('author_id', ['options' => $users, 'disabled' => true]); ?>
        <?= $this->Form->control('title'); ?>
        <?= $this->Form->control('tags._ids', ['options' => $tags, 'data-widget' => 'select']); ?>
        <?= $this->Form->control('categories._ids', ['options' => $categories, 'data-widget' => 'select']); ?>
        <?= $this->Form->control('excerpt'); ?>
        <?= $this->Form->control('body'); ?>
        <?=
        $this->Form->control('seo_title', [
            'append' => $this->Form->button('<i class="fa-solid fa-sync-alt"></i>', [
                'type' => 'button',
                'class' => 'btn btn-secondary',
                'id' => 'seo-title-refresh',
                'title' => __('Copy from article title'),
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
                'title' => __('Copy from article excerpt'),
                'escapeTitle' => false
            ])
        ]);
        ?>
        <?= $this->Form->control('seo_keywords'); ?>
        <?= $this->Form->control('published', ['switch' => true]); ?>
    </div>
    <div class="card-footer">
        <?= $this->element('form/save_buttons') ?>
        <?= $this->Html->link('<i class="fa-solid fa-times-circle"></i> ' . __('Cancel'), ['action' => 'index', '?' => $this->request->getQueryParams()], ['class' => 'btn btn-outline-danger', 'escape' => false]) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
