<?php
/**
 * @var \App\View\AppView $this
 * @var object $block
 * @var array<\Blogger\Model\Entity\Comment>|\Cake\Collection\CollectionInterface<\Blogger\Model\Entity\Comment> $comments
 */
?>
<div class="card mb-2">
    <h5 class="card-header">
        <?= $block->title ?>
    </h5>
    <ul class="list-unstyled ms-3 mb-0">
        <?php foreach ($comments as $comment) : ?>
            <li class="my-1">
                <?php if (isset($comment->user->full_name)) : ?>
                    <?= $comment->user->full_name ?> - 
                <?php else : ?>
                    <?= $comment->author_name ?> - 
                <?php endif; ?>
                <?=
                $this->Html->link($comment->article->title, [
                    'plugin' => 'Blogger', 'controller' => 'Articles', 'action' => 'view', 'id' => $comment->article->id, '#' => 'comment-' . $comment->id]);
                ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
