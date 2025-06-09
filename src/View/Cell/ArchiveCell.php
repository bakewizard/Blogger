<?php
declare(strict_types=1);

namespace Blogger\View\Cell;

use App\View\Cell\BlockCell as Cell;

/**
 * Archive cell
 */
class ArchiveCell extends Cell
{
    /**
     * Calendar
     *
     * Displays a calendar with archives
     *
     * @return void
     */
    public function display(): void
    {
        $currentYear = intval($this->request->getParam('year', date('Y')));
        $currentMonth = intval($this->request->getParam('month', date('m')));

        $query = $this->fetchTable('Blogger.Articles')->find(
            'list',
            keyField: 'day',
            valueField: 'articles_count',
        )->find('published');

        $days = $query->select([
                    'year' => 'YEAR(created)',
                    'day' => 'DAY(created)',
                    'articles_count' => $query->func()->count('*'),
                ])
                ->where(['YEAR(created)' => $currentYear, 'MONTH(created)' => $currentMonth])
                ->groupBy('day')
                ->toArray();

        $this->set(compact('days'));
    }
}
