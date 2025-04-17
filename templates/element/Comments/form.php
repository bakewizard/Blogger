<?= $this->Form->create(null, ['url' => ['plugin' => 'Blogger', 'controller' => 'Articles', 'action' => 'addComment']]) ?>
<?= $this->Form->hidden('article_id', ['val' => $article_id]); ?>
<?php if (isset($parent_id)): ?>
    <?= $this->Form->hidden('parent_id', ['val' => $parent_id]); ?>
<?php endif; ?>
<?php if (isset($user_id)): ?>
    <?= $this->Form->hidden('user_id', ['val' => $user_id]); ?>
<?php endif; ?>
<?php if ($this->Auth->isLoggedIn()): ?>
    <?= $this->Form->hidden('author_name', ['val' => $this->Auth->get('first_name')]); ?>
    <?= $this->Form->hidden('author_email', ['val' => $this->Auth->get('email')]); ?>
<?php endif; ?>

<?= $this->Form->control('content', ['label' => __d('blogger', 'Comment'), 'rows' => '3']); ?>
<?php if (!$this->Auth->isLoggedIn() && !$config['Blogger']['comments']['registration']): ?>
    <div class="row">
        <div class="col-6">
            <?= $this->Form->control('author_name', ['label' => __d('blogger', 'Name')]); ?>
        </div>
        <div class="col-6">
            <?= $this->Form->control('author_email', ['label' => __d('blogger', 'Email')]); ?>
        </div>
    </div>
<?php endif; ?>
<?= $this->Form->button(__d('blogger', 'Send'), ['class' => 'btn btn-primary text-light']) ?>
<?= $this->Form->end() ?>