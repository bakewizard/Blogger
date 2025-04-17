<?php
$stack = '';
$crumbsCount = count($crumbs);
$lastIdx = $crumbsCount - 1;
?>            
<ol class="breadcrumb mb-0">
    <?php if ($crumbsCount >= 1): ?>
        <li class="breadcrumb-item"><?= $this->Html->link('<i class="fa-solid fa-home"></i>', ['action' => 'index', '?' => $this->request->getQueryParams()], ['escape' => false]) ?></li>
    <?php endif; ?>

    <?php foreach ($crumbs as $i => $crumb): ?>
        <?php $stack .= '/' . $crumb->name; ?>
        <?php if ($i !== $lastIdx): ?>
            <li class="breadcrumb-item">
                <?= $this->Html->link($crumb->name, ['action' => 'index', $crumb->id, '?' => $this->request->getQueryParams()], ['class' => 'text-decoration-none']) ?>
            </li>
        <?php else: ?>
            <li class="breadcrumb-item"><?= $crumb->name ?></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ol>
