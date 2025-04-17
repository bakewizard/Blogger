<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-eye me-2"></i><strong><?= $article->title ?></strong></div>
    </div>
    <div class="card-body table-responsive p-3">
        <?= $this->Text->autoParagraph($article->body); ?>
    </div>
</div>
