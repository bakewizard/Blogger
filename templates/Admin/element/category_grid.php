<?php
/**
 * @var \App\View\AppView $this
 * @var array $config
 * @var array<\Blogger\Model\Entity\Category>|\Cake\Collection\CollectionInterface<\Blogger\Model\Entity\Category> $categories
 */
?>
<div id="categories" class="row">
    <?php foreach ($categories as $category) : ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xxl-2 mb-3" data-id="<?= $category->id ?>" data-url="<?= $this->Url->build(['action' => 'move']) ?>">
            <div class="card text-center" >
                <div class="card-header drag-handle">
                    <?= $this->Text->truncate($category->name, 24, ['exact' => false]) ?>
                </div>
                <div class="card-body">
                    <?=
                    $this->Html->image($this->getImageUrl($category, 'md'), [
                        'class' => 'img-fluid',
                        'alt' => $category->name,
                        'width' => $config['Shop']['categoryImages']['md'],
                        'height' => $config['Shop']['categoryImages']['md'],
                        'url' => ['action' => 'index', $category->id, '?' => $this->request->getQueryParams()],
                    ]);
                    ?>
                </div>
                <div class="card-footer text-center text-body-secondary">
                    <?= $this->Html->link('<i class="fa-solid fa-edit fa-fw fa-lg"></i>', ['action' => 'edit', $category->id, '?' => $this->request->getQueryParams()], ['escape' => false, 'class' => '']) ?>
                    <?=
                    $this->Form->deleteLink(
                        '<i class="fa-solid fa-trash text-danger fa-fw fa-lg"></i>',
                        ['action' => 'delete', $category->id, '?' => $this->request->getQueryParams()],
                        [
                                'block' => true,
                                'escape' => false,
                                'confirm' => __('Are you sure you want to delete {0}?', $category->name),
                                'class' => '',
                                'data-bs-toggle' => 'modal',
                                'data-bs-target' => '#confirm-modal',
                            ],
                    )
                    ?>
                    <?= $category->enabled ? '<i class="fa-solid fa-check text-success fa-fw fa-lg"></i>' : '<i class="fa-solid fa-xmark text-danger fa-fw fa-lg"></i>' ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>