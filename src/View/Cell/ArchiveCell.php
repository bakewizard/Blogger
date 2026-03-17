<?php
declare(strict_types=1);

namespace Blogger\View\Cell;

use App\Attribute\Link;
use App\View\Cell\BlockCell as Cell;
use Cake\I18n\Date;
use const CAL_GREGORIAN;

/**
 * Archive cell
 *
 * Fetches article counts per day for the currently displayed month
 * and passes them to the Cell template for rendering via CalendarHelper.
 */
class ArchiveCell extends Cell
{
    /**
     * Displays a calendar with daily article archive links.
     *
     * @return void
     */
    #[Link(summary: 'Archive', description: 'Displays a calendar with daily article archive links')]
    public function display(): void
    {
        $today = Date::now();
        $currentYear = max(2000, min(
            (int)$today->format('Y') + 1,
            (int)$this->request->getParam('year', $today->format('Y')),
        ));
        $currentMonth = max(1, min(
            12,
            (int)$this->request->getParam('month', $today->format('m')),
        ));

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
        $start = sprintf('%04d-%02d-01', $currentYear, $currentMonth);
        $end = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $daysInMonth);

        $query = $this->fetchTable('Blogger.Articles')
            ->find('list', keyField: 'day', valueField: 'articles_count')
            ->find('published');

        $days = $query
            ->select([
                'day' => $query->func()->day(['created' => 'identifier']),
                'articles_count' => $query->func()->count('*'),
            ])
            ->where([
                'created >=' => $start,
                'created <=' => $end,
            ])
            ->groupBy($query->func()->day(['created' => 'identifier']))
            ->toArray();

        $this->set(compact('days'));
    }
}
