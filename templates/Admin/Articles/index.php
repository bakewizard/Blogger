<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $_isSearch
 * @var array<\Blogger\Model\Entity\Article>|\Cake\Collection\CollectionInterface<\Blogger\Model\Entity\Article> $articles
 */
?>
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list me-2"></i><?= __('Articles list') ?></div>
        <div class="card-tools">
            <?= $this->Html->link('<i class="fa-solid fa-plus-circle"></i>', ['action' => 'add'], ['class' => 'btn btn-sm btn-outline-success', 'escape' => false]) ?>
            <?= $this->Form->button('<i class="fa-solid fa-minus-circle"></i> ', ['form' => 'index-form', 'class' => 'btn btn-sm btn-outline-danger', 'escapeTitle' => false]) ?>
        </div>
    </div>

    <div class="card-header">
        <?= $this->Form->create(null, ['valueSources' => 'query', 'class' => 'filter-form', 'id' => 'filter-form']); ?>
        <div class="row">
            <div class="col-sm-4">
                <?= $this->Form->control('title'); ?>
            </div>
            <div class="col-sm-4">  
                <label class="form-label" for="input-name"><?= __('Created') ?></label>
                <div class="input-group input-daterange">
                    <?= $this->Form->text('created_from', ['data-widget' => 'datepicker', 'data-format' => 'yyyy-mm-dd', 'id' => 'date-range-from-input']); ?>
                    <span class="input-group-text"></span>
                    <?= $this->Form->text('created_to', ['data-widget' => 'datepicker', 'data-format' => 'yyyy-mm-dd', 'id' => 'date-range-to-input']); ?>
                </div>
            </div>
            <div class="col-sm-4"> 
                <?= $this->Form->control('published', ['options' => [1 => 'Yes', 0 => 'No'], 'empty' => '---']); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">         
                <button type="submit" id="button-filter" class="btn btn-outline-success"><i class="fa-solid fa-search"></i> <?= __('Filter') ?></button>
                <?php if (!empty($_isSearch)): ?>
                    <?= $this->Html->link('<i class="fa-solid fa-times-circle"></i> ' . __('Clear'), ['controller' => 'Articles', 'action' => 'index'], ['class' => 'btn btn-outline-danger', 'escape' => false]) ?>
                <?php endif; ?>
            </div>
        </div>
        <?= $this->Form->end(); ?>
    </div>

    <?= $this->element('form/pager_top') ?>

    <div class="card-body">
        <div class="table-responsive">
            <?= $this->Form->create(null, ['id' => 'index-form', 'url' => ['action' => 'deleteMany', '?' => $this->request->getQueryParams()]]); ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center"><?= $this->Form->checkbox('check-all', ['id' => 'toggle-checkbox', 'hiddenField' => false]) ?></th>
                        <th><?= $this->Paginator->sort('author_id') ?></th>
                        <th><?= $this->Paginator->sort('title') ?></th>
                        <th><?= __('Categories') ?></th>
                        <th><?= $this->Paginator->sort('created') ?></th>
                        <th><?= $this->Paginator->sort('published') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td class="text-center align-middle"><?= $this->Form->checkbox('ids[]', ['hiddenField' => false, 'value' => $article->id]) ?></td>
                            <td><?= h($article->user->full_name) ?></td>
                            <td><?= h($article->title) ?></td>
                            <td>
                                <?php foreach ($article->categories as $category): ?>
                                    <p><?= $category->name ?></p>
                                <?php endforeach; ?>
                            </td>
                            <td><?= h($article->created) ?></td>
                            <td class="text-center">
                                <?= $article->published ? '<i class="fa-solid fa-check text-success fa-lg"></i>' : '<i class="fa-solid fa-xmark text-danger fa-lg"></i>' ?>
                            </td>
                            <td class="text-center actions">                                   
                                <?= $this->Html->link('<i class="fa-solid fa-eye"></i>', ['action' => 'view', $article->id], ['escape' => false, 'class' => 'btn btn-outline-primary']) ?>
                                <?= $this->Html->link('<i class="fa-solid fa-edit"></i>', ['action' => 'edit', $article->id, '?' => $this->request->getQueryParams()], ['escape' => false, 'class' => 'btn btn-outline-success']) ?>
                                <?=
                                $this->Form->deleteLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $article->id, '?' => $this->request->getQueryParams()],
                                        [
                                            'block' => true,
                                            'escape' => false,
                                            'confirm' => __('Are you sure you want to delete {0}?', $article->title),
                                            'class' => 'btn btn-outline-danger',
                                            'data-bs-toggle' => 'modal',
                                            'data-bs-target' => '#confirm-modal'
                                        ]
                                )
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?= $this->Form->end(); ?>
        </div>
    </div>

    <?= $this->element('form/pager_bottom') ?>
</div>