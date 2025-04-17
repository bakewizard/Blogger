<?php $this->assign('page', __('Blog Settings')); ?>
<div class="card card-success card-outline card-tabs">
    <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="config-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="config-tabs-main-tab" data-bs-toggle="pill" href="#config-tabs-main" role="tab" aria-controls="config-tabs-main" aria-selected="true"><?= __('Main') ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="config-tabs-comments-tab" data-bs-toggle="pill" href="#config-tabs-comments" role="tab" aria-controls="config-tabs-comments" aria-selected="false"><?= __('Comments') ?></a>
            </li>
        </ul>
    </div>
    <?= $this->Form->create($settings, ['align' => 'horizontal']) ?>
    <div class="card-body">
        <div class="tab-content" id="config-tabs-content">
            <div class="tab-pane fade show active" id="config-tabs-main" role="tabpanel" aria-labelledby="config-tabs-main-tab">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><?= __('General') ?></div>
                    </div>
                    <div class="card-body">
                        <?= $this->Form->control('articlesPerPage'); ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="config-tabs-comments" role="tabpanel" aria-labelledby="config-tabs-comments-tab">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><?= __('Comments') ?></div>
                    </div>
                    <div class="card-body">
                        <?= $this->Form->control('comments.sorting', ['options' => ['desc' => 'Newer first', 'asc' => 'Newer last']]); ?>
                        <?= $this->Form->control('comments.depth'); ?>
                        <?= $this->Form->control('comments.registration', ['label' => __('Users must be registered and logged in to comment')]); ?>
                        <?= $this->Form->control('comments.moderation', ['label' => __('Comments must be manually approved')]); ?>
                        <?= $this->Form->control('comments.notification', ['label' => __('Notify by email when comment arrives')]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <?= $this->Form->button('<i class="fa-solid fa-save"></i> ' . __('Save'), ['class' => 'btn-outline-success float-end', 'escapeTitle' => false]) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
