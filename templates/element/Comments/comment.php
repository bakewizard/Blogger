<?php
/**
 * @var \App\View\AppView $this
 * @var array $config
 * @var \Blogger\Model\Entity\Comment $comment
 */
?>
<div id="comment-<?= $comment->id ?>" class="d-flex mt-3">
    <div class="flex-shrink-0">
        <?=
        $this->Html->image($this->getImageUrl($comment->get('user'), 'sm'), [
            'title' => $comment->user->full_name ?? null,
            'alt' => $comment->user->full_name ?? null,
            'width' => 60,
            'height' => 60
        ]);
        ?>
    </div>
    <div class="flex-grow-1 ms-3">
        <h6 class="mt-0">
            <?php if ($comment->hasValue('user')): ?>
                <?= $this->Html->link($comment->user->full_name, ['plugin' => 'Blogger', 'controller' => 'Articles', 'action' => 'user', 'id' => $comment->user->id]); ?> • 
                <time datetime="<?= $comment->created ?>"><small><?= $comment->created ?></small></time>
            <?php else: ?>
                <?= $comment->author_name ?> • <time datetime="<?= $comment->created ?>"><small><?= $comment->created ?></small></time>
            <?php endif; ?>
        </h6>
        <div class="my-2">
            <?= h($comment->content); ?>
        </div>
        <?php if (($this->Auth->isLoggedIn() || !$config['Blogger']['comments']['registration']) && !$this->Auth->isUserLoggedIn($comment->user) && $comment->level != $config['Blogger']['comments']['depth']): ?>
            <div>
                <a href="#reply-block-<?= $comment->id ?>" data-bs-toggle="collapse" data-id="<?= $comment->id ?>"><i class="bi bi-reply me-2"></i><?= __d('blogger', 'Reply') ?></a>
            </div>
            <div id="reply-block-<?= $comment->id ?>" class="collapse mt-2">
                <?= $this->element('Comments/form', ['parent_id' => $comment->id, 'article_id' => $comment->article_id, 'user_id' => $this->Auth->get('id')]) ?>
            </div>
        <?php endif; ?>
        <?php if ($comment->hasValue('children')): ?>
            <?= $this->element('Comments/list', ['comments' => $comment->children]) ?>
        <?php endif; ?>
    </div>
</div>
