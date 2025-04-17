<div class="card mb-2">
    <h5 class="card-header">
        <?= $block->title ?>
    </h5>
    <ul class="list-unstyled ms-3 mb-0">
        <?php foreach ($articles as $article): ?>
            <li>
                <?=
                $this->Html->link($article->title, [
                    'plugin' => 'Blogger', 'controller' => 'Articles', 'action' => 'view', 'id' => $article->id]);
                ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
