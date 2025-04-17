<?= $this->Html->script('Blogger.backend/categories/index', ['block' => true]); ?>
<div class="card">
    <div class="card-header with-border">
        <h3 class="card-title">
            <div class="btn-group">
                <?php $view = $this->request->getQuery('view', 'list'); ?>
                <?=
                $this->Html->link('<i class="fa-solid fa-list-ul"></i>', ['?' => ['view' => null] + $this->request->getQueryParams()] + $this->request->getParam('pass'), [
                    'role' => 'button',
                    'rel' => 'nofollow',
                    'title' => __('List'),
                    'escape' => false,
                    'class' => 'btn btn-sm ' . ($view === 'list' ? 'btn-primary' : 'btn-outline-primary')
                ])
                ?>
                <?=
                $this->Html->link('<i class="fa-solid fa-table-cells-large"></i>', ['?' => ['view' => 'grid'] + $this->request->getQueryParams()] + $this->request->getParam('pass'), [
                    'role' => 'button',
                    'rel' => 'nofollow',
                    'title' => __('Grid'),
                    'escape' => false,
                    'class' => 'btn btn-sm ' . ($view === 'grid' ? 'btn-primary' : 'btn-outline-primary')
                ])
                ?>
            </div>
        </h3>
        <div class="card-tools">
            <?= $this->Html->link('<i class="fa-solid fa-plus-circle"></i>', ['action' => 'add', '?' => $this->request->getQueryParams()], ['class' => 'btn btn-sm btn-outline-success', 'escape' => false]) ?>
        </div>
    </div>

    <?php if (!empty($crumbs)): ?>
        <div class="card-header with-border">
            <?= $this->element('category_crumbs') ?>
        </div>
    <?php endif; ?>

    <div class="card-body">
        <?= $this->Form->create(null, ['id' => 'index-form', 'url' => ['action' => 'move', '?' => $this->request->getQueryParams()]]); ?>
        <?= $this->Form->hidden('id'); ?>
        <?= $this->Form->hidden('oldIndex'); ?>
        <?= $this->Form->hidden('newIndex'); ?>
        <?= $this->element('category_' . $view) ?>
        <?= $this->Form->end(); ?>
    </div>
</div>
