<?php foreach ($comments as $i => $comment): ?>
    <?php if ($comment->approved && $comment->level <= $config['Blogger']['comments']['depth']): ?>
        <?= $this->element('Comments/comment', ['comment' => $comment]) ?>
        <?php if (!isset($comment->parent_id) && $i + 1 != count($comments)): ?>
            <hr/>
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; ?>