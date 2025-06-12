<?php
/**
 * @var \App\View\AppView $this
 * @var array<\Blogger\Model\Entity\Comment>|\Cake\Collection\CollectionInterface<\Blogger\Model\Entity\Comment> $comments
 */
?>
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list me-2"></i><?= __('Comments list') ?></div>
        <div class="card-tools">
            <?= $this->Form->button('<i class="fa-solid fa-minus-circle"></i> ', ['form' => 'index-form', 'class' => 'btn btn-sm btn-outline-danger', 'escapeTitle' => false]) ?>
        </div>
    </div>

    <?= $this->element('form/pager_top') ?>

    <div class="card-body">
        <div class="table-responsive">
            <?= $this->Form->create(null, ['id' => 'index-form', 'url' => ['action' => 'deleteMany', '?' => $this->request->getQueryParams()]]); ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="text-center"><?= $this->Form->checkbox('check-all', ['id' => 'toggle-checkbox', 'hiddenField' => false]) ?></th>
                        <th scope="col"><?= $this->Paginator->sort('user_id', 'Author') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('content', 'Comment') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('parent_id', __('In Response To')) ?></th>
                        <th scope="col"><?= $this->Paginator->sort('approved') ?></th>
                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $comment): ?>
                        <tr>
                            <td class="text-center align-middle"><?= $this->Form->checkbox('ids[]', ['hiddenField' => false, 'value' => $comment->id]) ?></td>
                            <td>
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <?=
                                        $this->Html->image($this->getImageUrl($comment->get('user'), 'sm'), [
                                            'title' => $comment->user->full_name ?? null,
                                            'alt' => $comment->user->full_name ?? null,
                                            'class' => 'img-thumbnail',
                                            'width' => 60,
                                            'height' => 60
                                        ]);
                                        ?>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <?php if ($comment->hasValue('user')): ?>
                                            <h6 class="mt-0">
                                                <?= $comment->user->full_name ?>
                                            </h6>
                                            <?= $comment->user->email ?>
                                        <?php else: ?>
                                            <h6 class="mt-0"><?= $comment->author_name ?></h6>
                                            <?= $comment->author_email ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="my-1">
                                    <span class="me-2 text-muted"><?= __('Submitted on:') ?></span> <i class="fa-regular fa-clock"></i> <time><?= $comment->created ?></time>
                                </div>
                                <div>                              
                                    <?= $this->Text->truncate($comment->content, 60, ['exact' => false]) ?>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <?= $this->Html->link('<i class="fa-solid fa-newspaper"></i> ' . $comment->article->title, ['controller' => 'Articles', 'action' => 'view', $comment->article->id], ['escape' => false]) ?>
                                </div>
                                <div>
                                    <?php if ($comment->hasValue('parent_comment')): ?>
                                        <?= $this->Html->link('<i class="fa-regular fa-comment-dots"></i> ' . 'Parent comment', ['controller' => 'Comments', 'action' => 'edit', $comment->parent_comment->id], ['escape' => false]) ?>
                                    <?php else: ?>
                                        <?= '---' ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <?= $comment->approved ? '<i class="fa-solid fa-check text-success fa-lg"></i>' : '<i class="fa-solid fa-xmark text-danger fa-lg"></i>' ?>
                            </td>
                            <td class="text-center actions">                                   
                                <?= $this->Html->link('<i class="fa-solid fa-eye"></i>', ['action' => 'view', $comment->id], ['escape' => false, 'class' => 'btn btn-outline-primary']) ?>
                                <?= $this->Html->link('<i class="fa-solid fa-edit"></i>', ['action' => 'edit', $comment->id, '?' => $this->request->getQueryParams()], ['escape' => false, 'class' => 'btn btn-outline-success']) ?>
                                <?=
                                $this->Form->deleteLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $comment->id, '?' => $this->request->getQueryParams()],
                                        [
                                            'block' => true,
                                            'escape' => false,
                                            'confirm' => __('Are you sure you want to delete # {0}?', $comment->id),
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