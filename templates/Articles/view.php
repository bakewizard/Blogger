<?php
/**
 * @var \App\View\AppView $this
 * @var array $config
 * @var \Blogger\Model\Entity\Article $article
 */
?>
<?php $this->assign('title', $article->seo_title); ?>
<?= $this->Html->meta('description', $article->seo_description, ['block' => true]); ?>
<?= $this->Html->meta('keywords', $article->seo_keywords, ['block' => true]); ?>

<article>
    <header>
        <h1><?= $article->title ?></h1>
        <div>
            <span class="me-2"><i class="bi bi-clock"></i> <time><?= $article->created ?></time></span>
            <span class="me-2">
                <i class="bi bi-person"></i>
                <?= $this->Html->link($article->user->full_name, [
                    'plugin' => 'Blogger',
                    'controller' => 'Articles',
                    'action' => 'user',
                    'id' => $article->user->id,
                ]); ?>
            </span>
            <span>
                <i class="bi bi-chat"></i>
                <?php if ($article->comments_count > 0) : ?>
                    <?= $this->Html->link($article->comments_count, [
                        'plugin' => 'Blogger',
                        'controller' => 'Articles',
                        'action' => 'view',
                        'id' => $article->id,
                        '#' => 'comments-thread',
                    ]); ?>
                <?php else : ?>
                    <?= $article->comments_count ?>
                <?php endif; ?>
            </span>
        </div>
    </header>
    <hr>
    <section>
        <?= $article->body ?>
    </section>
    <?php if (!empty($article->tags)) : ?>
        <footer>
            <i class="bi bi-tags"></i>
            <?php foreach ($article->tags as $tag) : ?>
                <?= $this->Html->link($tag->title, ['plugin' => 'Blogger', 'controller' => 'Articles', 'action' => 'tag', 'alias' => $tag->alias]) ?>
            <?php endforeach; ?>
        </footer>
    <?php endif; ?>
    <hr>
</article>

<?php if (!$this->Auth->isUserLoggedIn($article->user)) : ?>
    <div class="card my-4">
        <div class="card-body">
            <?php if ($this->Auth->isLoggedIn() || !$config['Blogger']['comments']['registration']) : ?>
                <h4><?= __d('blogger', 'Leave your comment') ?>:</h4>
                <?= $this->element('Comments/form', ['article_id' => $article->id, 'user_id' => $this->Auth->get('id')]) ?>
            <?php else : ?>
                <div class="alert alert-warning mb-0" role="alert">
                    <p><?= __d('blogger', 'You must be registered and logged in to leave comments') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<div id="comments-thread">
    <?= $this->element('Comments/list', ['comments' => $article->comments]) ?>
</div>
