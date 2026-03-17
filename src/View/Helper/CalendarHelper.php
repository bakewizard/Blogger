<?php
declare(strict_types=1);

namespace Blogger\View\Helper;

use Cake\Http\ServerRequest;
use Cake\I18n\Date;
use Cake\View\Helper;
use Override;
use const CAL_GREGORIAN;

/**
 * Provides atomic calendar rendering methods for use in Cell templates.
 *
 * Responsible for generating individual calendar components (navigation,
 * header, day cells) based on the current month from the request.
 * The overall HTML structure is handled by the Cell template.
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class CalendarHelper extends Helper
{
    /**
     * @var array<string>
     */
    public array $helpers = ['Html'];

    /**
     * @inheritDoc
     */
    protected array $_defaultConfig = [];

    private ServerRequest $request;

    /**
     * The first day of the current month, used for i18n month name.
     */
    private Date $firstOfMonth;

    /**
     * Year currently displayed in the calendar.
     */
    private int $currentYear;

    /**
     * Month currently displayed in the calendar (1–12).
     */
    private int $currentMonth;

    /**
     * Day selected via URL parameter, used to mark the active link.
     */
    private int $currentDay;

    /**
     * Total number of days in the displayed month.
     */
    private int $daysInMonth;

    /**
     * ISO day of the week (1=Mon … 7=Sun) of the first day of the month.
     */
    private int $firstDayOfTheWeek;

    /**
     * Today's date, cached to avoid repeated instantiation.
     */
    private Date $today;

    /**
     * @inheritDoc
     */
    #[Override]
    public function initialize(array $config): void
    {
        $this->request = $this->getView()->getRequest();
        $this->today = Date::now();

        $this->currentYear = max(2000, min(
            (int)$this->today->format('Y') + 1,
            (int)$this->request->getParam('year', $this->today->format('Y')),
        ));
        $this->currentMonth = max(1, min(
            12,
            (int)$this->request->getParam('month', $this->today->format('m')),
        ));
        $this->currentDay = (int)$this->request->getParam('day', $this->today->format('d'));

        $this->daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->currentMonth, $this->currentYear);

        $firstDayTimestamp = strtotime(sprintf('%04d-%02d-01', $this->currentYear, $this->currentMonth)) ?: time();
        $this->firstDayOfTheWeek = (int)date('N', $firstDayTimestamp);

        $this->firstOfMonth = Date::create($this->currentYear, $this->currentMonth, 1);
    }

    /**
     * Renders the full calendar widget for the current month.
     *
     * A convenience facade that combines navigation(), header(), weeks() and day()
     * into a single call. For full control over the HTML structure, use the
     * individual methods directly in your template instead.
     *
     * @param array<int, mixed>|null $days Associative array keyed by day number with articles.
     * @return string The rendered HTML calendar.
     */
    public function render(?array $days = null): string
    {
        $html = '<div id="archives-calendar">';
        $html .= $this->navigation();
        $html .= '<table class="table table-sm mb-0 text-center">';
        $html .= '<thead>' . $this->header() . '</thead>';
        $html .= '<tbody>';
        foreach ($this->weeks() as $week) {
            $html .= '<tr>';
            foreach ($week as $day) {
                $html .= '<td>' . $this->day($day, $days) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table></div>';

        return $html;
    }

    /**
     * Returns the calendar grid as an array of weeks, each containing day numbers.
     *
     * Empty cells (before the first day and after the last day) are represented as null.
     *
     * Example output for a month starting on Wednesday:
     * [
     *   [null, null, 1, 2, 3, 4, 5],
     *   [6, 7, 8, 9, 10, 11, 12],
     *   ...
     * ]
     *
     * @return array<array<int|null>> Array of weeks, each with 7 entries (null or day number).
     */
    public function weeks(): array
    {
        $weeks = [];
        $day = 1;
        $weeksInMonth = (int)ceil(($this->firstDayOfTheWeek - 1 + $this->daysInMonth) / 7);

        for ($week = 0; $week < $weeksInMonth; $week++) {
            $row = [];
            for ($weekDay = 1; $weekDay <= 7; $weekDay++) {
                $cell = $week * 7 + $weekDay;
                if ($cell >= $this->firstDayOfTheWeek && $day <= $this->daysInMonth) {
                    $row[] = $day++;
                } else {
                    $row[] = null;
                }
            }
            $weeks[] = $row;
        }

        return $weeks;
    }

    /**
     * Renders the navigation bar with prev/next month and year controls.
     *
     * Forward navigation buttons (next month, next year) are disabled when
     * they would point to a month beyond the current one, as no articles
     * can exist in the future.
     *
     * @return string The HTML navigation bar.
     */
    public function navigation(): string
    {
        $prevMonth = $this->currentMonth === 1 ? 12 : $this->currentMonth - 1;
        $prevYear = $this->currentMonth === 1 ? $this->currentYear - 1 : $this->currentYear;
        $nextMonth = $this->currentMonth === 12 ? 1 : $this->currentMonth + 1;
        $nextYear = $this->currentMonth === 12 ? $this->currentYear + 1 : $this->currentYear;

        $todayYear = (int)$this->today->format('Y');
        $todayMonth = (int)$this->today->format('m');

        $nextMonthDisabled = $nextYear > $todayYear
            || ($nextYear === $todayYear && $nextMonth > $todayMonth);
        $nextYearDisabled = $this->currentYear + 1 > $todayYear
            || ($this->currentYear + 1 === $todayYear && $this->currentMonth > $todayMonth);

        $monthTitle = mb_convert_case(
            (string)$this->firstOfMonth->i18nFormat('LLLL'),
            MB_CASE_TITLE,
            'UTF-8',
        );

        return '<div class="py-1 d-flex justify-content-between">'
            . $this->navLink('<i class="bi bi-chevron-double-left"></i>', $this->currentYear - 1, $this->currentMonth)
            . $this->navLink('<i class="bi bi-chevron-left"></i>', $prevYear, $prevMonth)
            . '<div class="fw-bold">' . $monthTitle . ' ' . $this->currentYear . '</div>'
            . $this->navLink('<i class="bi bi-chevron-right"></i>', $nextYear, $nextMonth, $nextMonthDisabled)
            . $this->navLink('<i class="bi bi-chevron-double-right"></i>', $this->currentYear + 1, $this->currentMonth, $nextYearDisabled)
            . '</div>';
    }

    /**
     * Renders the table header row with localized two-letter day names (Mon–Sun).
     *
     * Uses 2024-01-01 as a Monday anchor to generate day names via i18n formatting.
     *
     * @return string The HTML <tr> header row.
     */
    public function header(): string
    {
        $dow = [];
        for ($i = 1; $i <= 7; $i++) {
            $dow[$i] = Date::create(2024, 1, $i)->i18nFormat('EEEEEE');
        }

        return $this->Html->tableHeaders($dow, null, ['scope' => 'col']);
    }

    /**
     * Renders a single day cell.
     *
     * - Returns an empty string for null (empty grid cell).
     * - If the day has articles (or $days is null): renders a link.
     * - Today is wrapped in <strong>.
     * - The currently selected day (from URL) gets the `active` CSS class.
     *
     * @param int|null $day The day number, or null for an empty cell.
     * @param array<int, mixed>|null $days Associative array keyed by day number with articles.
     * @return string The HTML for the day cell content.
     */
    public function day(?int $day, ?array $days): string
    {
        if ($day === null) {
            return '';
        }

        $isToday = $day === (int)$this->today->format('d')
            && $this->currentMonth === (int)$this->today->format('m')
            && $this->currentYear === (int)$this->today->format('Y');

        $isSelected = $day === $this->currentDay;
        $hasArticles = $days === null || isset($days[$day]);
        $label = $isToday ? '<strong>' . $day . '</strong>' : (string)$day;

        if (!$hasArticles) {
            return $label;
        }

        $classes = 'text-primary fw-bold' . ($isSelected ? ' active' : '');

        return $this->Html->link($label, [
            'plugin' => 'Blogger',
            'controller' => 'Articles',
            'action' => 'archive',
            $this->currentYear,
            sprintf('%02d', $this->currentMonth),
            sprintf('%02d', $day),
        ], ['class' => $classes, 'escape' => false]);
    }

    /**
     * Renders a single navigation link.
     *
     * When disabled, renders a non-clickable <span> styled as a muted button
     * instead of an anchor tag.
     *
     * @param string $icon The HTML icon markup.
     * @param int $year Target year.
     * @param int $month Target month (1–12).
     * @param bool $disabled Whether the button should be non-clickable.
     * @return string The rendered anchor or span tag.
     */
    private function navLink(string $icon, int $year, int $month, bool $disabled = false): string
    {
        if ($disabled) {
            return '<span class="btn btn-sm text-muted">' . $icon . '</span>';
        }

        return $this->Html->link($icon, [
            'plugin' => 'Blogger',
            'controller' => 'Articles',
            'action' => 'archive',
            $year,
            sprintf('%02d', $month),
        ], ['escape' => false, 'class' => 'btn btn-sm']);
    }
}
