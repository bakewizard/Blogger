<?php
/**
 * @var \App\View\AppView $this
 * @var object $block
 * @var array<\Blogger\Model\Entity\Article>|\Cake\Collection\CollectionInterface<\Blogger\Model\Entity\Article> $articles
 */
?>
<?php if (!empty($articles)) : ?>
    <h4><?= $block->title ?>:</h4>
    <ul class="list-unstyled">
        <?php foreach ($articles as $article) : ?>
            <li>
                <?=
                $this->Html->link($article->title, [
                    'plugin' => 'Blogger', 'controller' => 'Articles', 'action' => 'view', 'id' => $article->id]);
                ?> 
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>