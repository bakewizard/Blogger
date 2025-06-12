<?php
/**
 * @var \App\View\AppView $this
 * @var \Blogger\Model\Entity\Comment $comment
 */
?>
<div class="card card-primary card-outline mb-3">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-eye me-2"></i><?= __('Comment') ?></div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover">
            <tr>
                <th scope="row"><?= __('Parent Comment') ?></th>
                <td>
                    <?= $comment->hasValue('parent_comment') ? $this->Html->link('<i class="fa-regular fa-comment-dots"></i>', ['controller' => 'Comments', 'action' => 'view', $comment->parent_comment->id], ['escape' => false]) : '---' ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?= __('User') ?></th>
                <td><?= $comment->hasValue('user') ? $comment->user->full_name : '---' ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Article') ?></th>
                <td><?= $comment->hasValue('article') ? $this->Html->link($comment->article->title, ['controller' => 'Articles', 'action' => 'view', $comment->article->id]) : '' ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Content') ?></th>
                <td><?= h($comment->content) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Created') ?></th>
                <td><?= h($comment->created) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Modified') ?></th>
                <td><?= h($comment->modified) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Approved') ?></th>
                <td>
                    <?= $comment->approved ? '<i class="fa-solid fa-check text-success fa-lg"></i>' : '<i class="fa-solid fa-xmark text-danger fa-lg"></i>' ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="card-header">
        <div class="card-title"><?= __('Related Comments') ?></div>
    </div>
    <?php if (!empty($comment->child_comments)): ?>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <tr>
                    <th scope="col"><?= __('User') ?></th>
                    <th scope="col"><?= __('Article') ?></th>
                    <th scope="col"><?= __('Content') ?></th>
                    <th scope="col"><?= __('Created') ?></th>
                    <th scope="col"><?= __('Modified') ?></th>
                    <th scope="col"><?= __('Approved') ?></th>
                </tr>
                <?php foreach ($comment->child_comments as $child_comment): ?>
                    <tr>
                        <td>
                            <?php if ($child_comment->hasValue('user')): ?>
                                <?= $child_comment->user->full_name ?>
                            <?php else: ?>
                                <?= $child_comment->author_name ?>
                            <?php endif; ?>
                        </td>
                        <td><?= h($child_comment->article->title) ?></td>
                        <td><?= h($this->Text->truncate($child_comment->content, 60, ['exact' => false])) ?></td>
                        <td><?= h($child_comment->created) ?></td>
                        <td><?= h($child_comment->modified) ?></td>
                        <td class="text-center">
                            <?= $comment->approved ? '<i class="fa-solid fa-check text-success fa-lg"></i>' : '<i class="fa-solid fa-xmark text-danger fa-lg"></i>' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>
