<?php
/**
 * @var \App\View\AppView $this
 * @var object $block
 * @var array<int, mixed>|null $days
 */
?>
<div class="card mb-2">
    <h5 class="card-header">
        <?= h($block->title) ?>
    </h5>
    <div class="card-body px-1 py-0 mb-2">
        <?= $this->Calendar->render($days) ?>
        <?php /*
        To customize the calendar structure, replace render() above
        with individual helper methods:

        <div id="archives-calendar">
            <?= $this->Calendar->navigation() ?>
            <table class="table table-sm mb-0 text-center">
                <thead><?= $this->Calendar->header() ?></thead>
                <tbody>
                    <?php foreach ($this->Calendar->weeks() as $week): ?>
                        <tr>
                            <?php foreach ($week as $day): ?>
                                <td><?= $this->Calendar->day($day, $days) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        */ ?>
    </div>
</div>
