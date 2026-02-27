<?php
/**
 * @var \App\View\AppView $this
 * @var \Blogger\Model\Entity\Article $article
 * @var \Blogger\Model\Entity\Comment $comment
 */
?>
<p>
    <?= __d('blogger', 'You have a new comment to your article') ?>: 
    <?=
    $this->Html->link($article->title, [
        'plugin' => 'Blogger',
        'prefix' => false,
        'controller' => 'Articles',
        'action' => 'view',
        'id' => $article->id,
        '#' => 'comment-' . $comment->id,
        '_full' => true,
    ])
    ?>
</p>
