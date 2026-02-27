<?php
/**
 * @var \App\View\AppView $this
 * @var object $block
 * @var array<\Blogger\Model\Entity\Tag>|\Cake\Collection\CollectionInterface<\Blogger\Model\Entity\Tag> $tags
 */
?>
<div class="card mb-2">
    <h5 class="card-header">
        <?= $block->title ?>
    </h5>
    <ul class="list-unstyled ms-3 mb-0">
        <?php foreach ($tags as $tag) : ?>
            <li class="my-1">
                <?=
                $this->Html->link($tag->title, [
                    'plugin' => 'Blogger', 'controller' => 'Articles', 'action' => 'tag', 'alias' => $tag->alias]);
                ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
