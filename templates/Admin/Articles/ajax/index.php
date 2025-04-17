<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('author_id') ?></th>
                        <th><?= $this->Paginator->sort('title') ?></th>
                        <th><?= $this->Paginator->sort('published') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td>
                                <?= $article->hasValue('user') ? $article->user->full_name : '' ?>
                            </td>
                            <td>
                                <?= $this->Html->link($article->title, ['plugin' => 'Blogger', 'prefix' => false, 'controller' => 'Articles', 'action' => 'view', $article->id], ['role' => 'button']); ?>
                            </td>
                            <td class="text-center">
                                <?= $article->published ? '<i class="fa-solid fa-check text-success fa-lg"></i>' : '<i class="fa-solid fa-xmark text-danger fa-lg"></i>' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        <ul class="pagination pagination-sm no-margin float-end">
            <?= $this->Paginator->first('<i class="fa-solid fa-step-backward"></i>', ['escape' => false]); ?>
            <?= $this->Paginator->prev('<i class="fa-solid fa-backward"></i>', ['escape' => false]); ?>
            <?= $this->Paginator->numbers(); ?>
            <?= $this->Paginator->next('<i class="fa-solid fa-forward"></i>', ['escape' => false]); ?>
            <?= $this->Paginator->last('<i class="fa-solid fa-step-forward"></i>', ['escape' => false]); ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>