<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $children
 */
?>
<ul class="list-unstyled ms-2 mb-0">
    <?php foreach ($children as $child): ?>
        <li class="p-1">
            <?=
            $this->Html->link($child->name, [
                'plugin' => 'Blogger', 'controller' => 'Articles', 'action' => 'category', 'id' => $child->id]);
            ?>
            (<?= $child->articles_count ?>)
            <?php if ($child->hasValue('children') && !empty($child->children)): ?>
                <?= $this->element('categories', ['children' => $child->children]) ?>
            <?php endif; ?>        
        </li>
    <?php endforeach; ?>
</ul>