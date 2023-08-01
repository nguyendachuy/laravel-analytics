<?php

namespace NguyenHuy\Analytics;

use Carbon\Carbon;
use DateTimeInterface;
use Google\Analytics\Data\V1beta\DateRange;
use NguyenHuy\Analytics\Exceptions\InvalidPeriod;

class Period
{
    public $startDate;

    public $endDate;

    public static function create(DateTimeInterface $startDate, DateTimeInterface $endDate): self
    {
        return new static($startDate, $endDate);
    }

    public static function days(int $numberOfDays)
    {
        $endDate = Carbon::today();

        $startDate = Carbon::today()->subDays($numberOfDays)->startOfDay();

        return new static($startDate, $endDate);
    }

    public static function months(int $numberOfMonths)
    {
        $endDate = Carbon::today();

        $startDate = Carbon::today()->subMonths($numberOfMonths)->startOfDay();

        return new static($startDate, $endDate);
    }

    public static function years(int $numberOfYears)
    {
        $endDate = Carbon::today();

        $startDate = Carbon::today()->subYears($numberOfYears)->startOfDay();

        return new static($startDate, $endDate);
    }

    public function __construct(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        if ($startDate > $endDate) {
            throw InvalidPeriod::startDateCannotBeAfterEndDate($startDate, $endDate);
        }

        $this->startDate = $startDate;

        $this->endDate = $endDate;
    }

    public function toDateRange()
    {
        return (new DateRange())
            ->setStartDate($this->startDate->format('Y-m-d'))
            ->setEndDate($this->endDate->format('Y-m-d'));
    }
}
