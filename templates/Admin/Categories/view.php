<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $crumbs
 * @var \Blogger\Model\Entity\Category $category
 */
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fa-solid fa-eye me-2"></i><?= h($category->name) ?>
        </h3>
    </div>

    <?php if (!empty($crumbs)): ?>
        <div class="card-header with-border">
            <?= $this->element('category_crumbs') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($category->articles)): ?>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <colgroup>
                        <col class="col-9">
                        <col class="col-1">
                        <col class="col-1">
                        <col class="col-1">
                    </colgroup>
                    <tr>
                        <th><?= __('Title') ?></th>
                        <th><?= __('Sort Order') ?></th>
                        <th class="text-center"><?= __('Published') ?></th>
                        <th></th>
                    </tr>
                    <?php foreach ($category->articles as $i => $article): ?>
                        <?= $this->Form->create($article, ['id' => "article-form-{$i}", 'url' => ['action' => 'editArticle', $article->id]]); ?>
                        <tr>
                            <td><?= h($article->title) ?></td>
                            <td><?= $this->Form->control('sort_order', ['label' => false]); ?></td>
                            <td class="d-flex justify-content-center">
                                <?= $this->Form->control('published', ['label' => false, 'switch' => true]); ?>
                            </td>
                            <td>
                                <?= $this->Form->button('<i class="fa-solid fa-save"></i>', ['class' => 'btn btn-outline-success', 'escapeTitle' => false]) ?>
                            </td>
                        </tr>
                        <?= $this->Form->end(); ?>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>