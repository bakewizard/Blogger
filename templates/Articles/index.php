<?php
/**
 * @var \App\View\AppView $this
 * @var object $meta
 * @var array<\Blogger\Model\Entity\Article>|\Cake\Collection\CollectionInterface<\Blogger\Model\Entity\Article> $articles
 */
?>
<?php if (isset($meta)) : ?>
    <?php $this->assign('title', $meta->seo_title); ?>
    <?= $this->Html->meta('description', $meta->seo_description, ['block' => true]); ?>
    <?= $this->Html->meta('keywords', $meta->seo_keywords, ['block' => true]); ?>
<?php endif; ?>

<?= $this->Paginator->meta(['block' => true]); ?>

<section id="blog-body" class="blog-body">
    <?php if (!$articles->items()->isEmpty()) : ?>
        <?php foreach ($articles as $article) : ?>
            <article>
                <header>
                    <h1>
                        <?= $this->Html->link($article->title, ['plugin' => 'Blogger', 'controller' => 'Articles', 'action' => 'view', $article->id]) ?>
                    </h1>
                    <p>
                        <span class="me-2"><i class="bi bi-clock"></i> <time><?= $article->created ?></time></span>
                        <span class="me-2"><i class="bi bi-person"></i>
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
                    </p>
                </header>
                <section>
                    <?php if (!empty($article->excerpt)) : ?>
                        <?= $article->excerpt ?>
                    <?php else : ?>
                        <?= $this->Text->truncate($article->body, 500, ['exact' => true, 'html' => true]) ?>
                    <?php endif; ?>
                </section>
                <footer>
                    <?= $this->Html->link(__d('blogger', 'Read more') . ' <i class="bi bi-chevron-right"></i>', [
                        'plugin' => 'Blogger',
                        'controller' => 'Articles',
                        'action' => 'view',
                        $article->id,
                    ], ['escape' => false, 'class' => 'btn btn-primary text-light']) ?>
                </footer>
            </article>
            <hr>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="alert alert-warning" role="alert">
            <?= __d('blogger', 'Nothing found') ?>
        </div>
    <?php endif; ?>
</section>

<?php if ($this->Paginator->params()['pageCount'] > 1) : ?>
    <footer class="blog-footer">
        <div class="row mb-4">
            <nav class="col-12 d-flex justify-content-center" aria-label="Page navigation">
                <ul id="pager" class="pagination mb-0">
                    <?= $this->Paginator->prev('<i class="bi-caret-left-fill"></i>', ['escape' => false]); ?>
                    <?= $this->Paginator->numbers(['first' => 3, 'last' => 3]); ?>
                    <?= $this->Paginator->next('<i class="bi-caret-right-fill"></i>', ['escape' => false]); ?>
                </ul>
            </nav>
        </div>
    </footer>
<?php endif; ?>
