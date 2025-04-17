<ul id="categories" class="list-group">
    <?php foreach ($categories as $category): ?>
        <li class="list-group-item  d-flex justify-content-between align-items-start" data-id="<?= $category->id ?>" data-url="<?= $this->Url->build(['action' => 'move']) ?>">
            <div class="me-auto">
                <div class="fw-bold"><i class="fa-solid fa-arrows-alt drag-handle"></i> 
                    <?= $this->Html->link($category->name, ['action' => 'index', $category->id, '?' => $this->request->getQueryParams()], ['escape' => false, 'class' => 'ms-2 text-decoration-none']) ?>
                </div>
            </div>
            <div>
                <?= $this->Html->link('<i class="fa-solid fa-edit fa-fw fa-lg"></i>', ['action' => 'edit', $category->id, '?' => $this->request->getQueryParams()], ['escape' => false, 'class' => '']) ?>
                <?=
                $this->Form->deleteLink('<i class="fa-solid fa-trash text-danger fa-fw fa-lg"></i>', ['action' => 'delete', $category->id, '?' => $this->request->getQueryParams()],
                        [
                            'block' => true,
                            'escape' => false,
                            'confirm' => __('Are you sure you want to delete {0}?', $category->name),
                            'class' => '',
                            'data-bs-toggle' => 'modal',
                            'data-bs-target' => '#confirm-modal'
                        ]
                )
                ?>
                <?= $category->enabled ? '<i class="fa-solid fa-check text-success fa-fw fa-lg"></i>' : '<i class="fa-solid fa-xmark text-danger fa-fw fa-lg"></i>' ?>
            </div>
        </li>
    <?php endforeach; ?>
</ul>