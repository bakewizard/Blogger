<?php
declare(strict_types=1);

namespace Blogger\View\Helper;

use Cake\I18n\Date;
use Cake\View\Helper;
use const CAL_GREGORIAN;

/**
 * Calendar helper
 */
class CalendarHelper extends Helper
{
    /**
     * Helpers used.
     *
     * @var array
     */
    public array $helpers = ['Html'];
    protected $request;
    protected $date;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected array $_defaultConfig = [];
    private $currentYear;
    private $currentMonth;
    private $currentDay;
    private $daysInMonth;
    private $firstDayOfTheWeek;

    public function initialize(array $config): void
    {
        $this->request = $this->getView()->getRequest();
        $this->date = Date::now();

        $this->currentYear = intval($this->request->getParam('year', date('Y')));
        $this->currentMonth = intval($this->request->getParam('month', date('m')));
        $this->currentDay = intval($this->request->getParam('day', date('d')));
        $this->daysInMonth = intval(cal_days_in_month(CAL_GREGORIAN, $this->currentMonth, $this->currentYear));
        $this->firstDayOfTheWeek = intval(date('N', strtotime($this->currentYear . '-' . $this->currentMonth . '-01')));
        $this->date->setDate($this->currentYear, $this->currentMonth, $this->currentDay);
    }

    /**
     * Displays the calendar
     */
    public function render($days = null): string
    {
        $content = '<div id="archives-calendar">';
        $content .= $this->createNavigation();
        $content .= '<table class="table table-sm mb-0 text-center">';
        $content .= '<thead>' . $this->createHeader() . '</thead>';
        $content .= '<tbody>';

        $weeksInMonth = $this->getWeeksInMonth();
        $day = 1;
        // Create weeks in a month
        for ($i = 0; $i < $weeksInMonth; $i++) {
            $content .= '<tr>';
            //Create days in a week
            for ($j = 1; $j <= 7; $j++) {
                $content .= '<td>';
                if (($i * 7 + $j) >= $this->firstDayOfTheWeek && $day <= $this->daysInMonth) {
                    if (!isset($days) || isset($days[$day])) {
                        $dayPadded = str_pad(strval($day), 2, '0', STR_PAD_LEFT);
                        $monthPadded = str_pad(strval($this->currentMonth), 2, '0', STR_PAD_LEFT);

                        $content .= $this->Html->link(strval($day), [
                            'plugin' => 'Blogger',
                            'controller' => 'Articles',
                            'action' => 'archive',
                            $this->currentYear, $monthPadded, $dayPadded,
                                ], ['class' => 'text-primary fw-bold']);
                    } else {
                        $content .= $day;
                    }

                    $day++;
                }
                $content .= '</td>';
            }
            $content .= '</tr>';
        }

        $content .= '</tbody>';
        $content .= '</table>';
        $content .= '</div>';

        return $content;
    }

    protected function createNavigation(): string
    {
        $nextMonth = $this->currentMonth == 12 ? 1 : $this->currentMonth + 1;
        $nextYear = $this->currentMonth == 12 ? $this->currentYear + 1 : $this->currentYear;
        $prevMonth = $this->currentMonth == 1 ? 12 : $this->currentMonth - 1;
        $prevYear = $this->currentMonth == 1 ? $this->currentYear - 1 : $this->currentYear;

        return '<div class="py-1 d-flex justify-content-between">' .
                $this->Html->link('<i class="bi bi-chevron-double-left"></i>', [
                    'plugin' => 'Blogger',
                    'controller' => 'Articles',
                    'action' => 'archive', $prevYear, sprintf('%02d', $prevMonth),
                        ], ['escape' => false, 'class' => 'btn btn-sm']) .
                $this->Html->link('<i class="bi bi-chevron-left"></i>', [
                    'plugin' => 'Blogger',
                    'controller' => 'Articles',
                    'action' => 'archive', $this->currentYear - 1, sprintf('%02d', $this->currentMonth),
                        ], ['escape' => false, 'class' => 'btn btn-sm']) .
                '<div class="fw-bold">' .
                mb_convert_case($this->date->i18nFormat('LLLL'), MB_CASE_TITLE, 'UTF-8') . ' ' . $this->currentYear .
                '</div>' .
                $this->Html->link('<i class="bi bi-chevron-right"></i>', [
                    'plugin' => 'Blogger',
                    'controller' => 'Articles',
                    'action' => 'archive', $this->currentYear + 1, sprintf('%02d', $this->currentMonth),
                        ], ['escape' => false, 'class' => 'btn btn-sm']) .
                $this->Html->link('<i class="bi bi-chevron-double-right"></i>', [
                    'plugin' => 'Blogger',
                    'controller' => 'Articles',
                    'action' => 'archive', $nextYear, sprintf('%02d', $nextMonth),
                        ], ['escape' => false, 'class' => 'btn btn-sm']) .
                '</div>';
    }

    protected function createHeader(): string
    {
        $dow = [];
        for ($i = 1; $i <= 7; $i++) {
            $newDay = $this->date->setDate(2019, 7, $i);
            $dow[$i] = $newDay->i18nFormat('EEEEEE');
        }

        return $this->Html->tableHeaders($dow, null, ['scope' => 'col']);
    }

    /**
     * Calculate a number of weeks in a particular month
     */
    private function getWeeksInMonth(): int
    {
        $numOfweeks = ($this->daysInMonth % 7 == 0 ? 0 : 1) + intval($this->daysInMonth / 7);
        $monthEndingDay = date('N', strtotime($this->currentYear . '-' . $this->currentMonth . '-' . $this->daysInMonth));
        $monthStartDay = date('N', strtotime($this->currentYear . '-' . $this->currentMonth . '-01'));

        if ($monthEndingDay < $monthStartDay) {
            $numOfweeks++;
        }

        return $numOfweeks;
    }
}
