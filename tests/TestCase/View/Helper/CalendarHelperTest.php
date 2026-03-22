<?php
declare(strict_types=1);

namespace Blogger\Test\TestCase\View\Helper;

use Blogger\View\Helper\CalendarHelper;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use ReflectionProperty;

/**
 * Blogger\View\Helper\CalendarHelper Test Case
 *
 * weeks() contains pure logic and is fully tested here.
 * day() tests are limited to cases that do not generate URLs (null cell,
 * day with no articles) to avoid Router dependency in unit tests.
 *
 * @uses \Blogger\View\Helper\CalendarHelper
 */
class CalendarHelperTest extends TestCase
{
    /**
     * Creates a CalendarHelper with injected year/month state,
     * bypassing initialize() which requires request params.
     */
    private function makeHelper(int $year, int $month, int $day = 1): CalendarHelper
    {
        $request = new ServerRequest();
        $view = new View($request);
        $helper = new CalendarHelper($view);

        $this->set($helper, 'currentYear', $year);
        $this->set($helper, 'currentMonth', $month);
        $this->set($helper, 'currentDay', $day);
        $this->set($helper, 'daysInMonth', cal_days_in_month(CAL_GREGORIAN, $month, $year));

        $firstDayTimestamp = strtotime(sprintf('%04d-%02d-01', $year, $month)) ?: time();
        $this->set($helper, 'firstDayOfTheWeek', (int)date('N', $firstDayTimestamp));

        return $helper;
    }

    private function set(CalendarHelper $helper, string $prop, mixed $value): void
    {
        $ref = new ReflectionProperty(CalendarHelper::class, $prop);
        $ref->setValue($helper, $value);
    }

    // -------------------------------------------------------------------------
    // weeks()
    // -------------------------------------------------------------------------

    /**
     * weeks() returns 7 entries per row.
     */
    public function testWeeksReturnsSevenDaysPerRow(): void
    {
        $helper = $this->makeHelper(2024, 1); // January 2024
        foreach ($helper->weeks() as $week) {
            $this->assertCount(7, $week);
        }
    }

    /**
     * weeks() covers all days in the month exactly once.
     */
    public function testWeeksCoversAllDaysExactlyOnce(): void
    {
        $helper = $this->makeHelper(2024, 1); // 31 days
        $days = array_filter(array_merge(...$helper->weeks()));
        $this->assertCount(31, $days);
        $this->assertSame(range(1, 31), array_values($days));
    }

    /**
     * January 2024 starts on Monday (ISO day 1) — no leading nulls.
     */
    public function testWeeksNoLeadingNullsWhenMonthStartsOnMonday(): void
    {
        $helper = $this->makeHelper(2024, 1);
        $firstWeek = $helper->weeks()[0];
        $this->assertSame(1, $firstWeek[0]);
    }

    /**
     * March 2024 starts on Friday (ISO day 5) — four leading nulls.
     */
    public function testWeeksLeadingNullsWhenMonthStartsMidWeek(): void
    {
        $helper = $this->makeHelper(2024, 3); // starts Friday
        $firstWeek = $helper->weeks()[0];
        $this->assertNull($firstWeek[0]); // Mon
        $this->assertNull($firstWeek[1]); // Tue
        $this->assertNull($firstWeek[2]); // Wed
        $this->assertNull($firstWeek[3]); // Thu
        $this->assertSame(1, $firstWeek[4]); // Fri
    }

    /**
     * February 2024 has 29 days (leap year).
     */
    public function testWeeksLeapYearFebruary(): void
    {
        $helper = $this->makeHelper(2024, 2);
        $days = array_filter(array_merge(...$helper->weeks()));
        $this->assertCount(29, $days);
    }

    /**
     * February 2023 has 28 days (non-leap year).
     */
    public function testWeeksNonLeapYearFebruary(): void
    {
        $helper = $this->makeHelper(2023, 2);
        $days = array_filter(array_merge(...$helper->weeks()));
        $this->assertCount(28, $days);
    }

    /**
     * December 2024 starts on Sunday (ISO day 7) — six leading nulls.
     */
    public function testWeeksSixLeadingNullsWhenStartsOnSunday(): void
    {
        $helper = $this->makeHelper(2024, 12); // starts Sunday
        $firstWeek = $helper->weeks()[0];
        $this->assertNull($firstWeek[0]); // Mon–Sat all null
        $this->assertNull($firstWeek[5]);
        $this->assertSame(1, $firstWeek[6]); // Sun
    }

    // -------------------------------------------------------------------------
    // day()
    // -------------------------------------------------------------------------

    /**
     * day(null, ...) returns empty string.
     */
    public function testDayNullReturnsEmpty(): void
    {
        $helper = $this->makeHelper(2024, 1);
        $this->assertSame('', $helper->day(null, []));
    }

    /**
     * day() returns plain label when day has no articles.
     */
    public function testDayWithoutArticlesReturnsPlainLabel(): void
    {
        $helper = $this->makeHelper(2024, 1);
        $result = $helper->day(15, []); // empty days array — no articles
        $this->assertSame('15', $result);
    }
}
