<?php

namespace NguyenHuy\Analytics;

use Google\Analytics\Data\V1beta\FilterExpression;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;

class Analytics
{
    use Macroable;
    private $client;
    private $propertyId;

    public function __construct(AnalyticsClient $client, string $propertyId)
    {
        $this->client = $client;
        $this->propertyId = $propertyId;
    }

    public function getPropertyId(): string
    {
        return $this->propertyId;
    }

    /**
     * @param  \NguyenHuy\Analytics\Period  $period
     * @return \Illuminate\Support\Collection<int, array{
     *   pageTitle: string,
     *   activeUsers: int,
     *   screenPageViews: int
     * }>
     */
    public function fetchVisitorsAndPageViews(Period $period, int $maxResults = 10, int $offset = 0, FilterExpression $dimensionFilter = null): Collection
    {
        return $this->get(
            $period,
            ['activeUsers', 'screenPageViews'],
            ['pageTitle', 'pagePath'],
            $maxResults,
            [
                OrderBy::dimension('screenPageViews', true)
            ],
            $offset,
            $dimensionFilter
        );
    }

    /**
     * @param  \NguyenHuy\Analytics\Period  $period
     * @return \Illuminate\Support\Collection<int, array{
     *   pageTitle: string,
     *   date: \Carbon\Carbon,
     *   activeUsers: int,
     *   screenPageViews: int
     * }>
     */
    public function fetchVisitorsAndPageViewsByDate(Period $period, int $maxResults = 10, $offset = 0, FilterExpression $dimensionFilter = null): Collection
    {
        return $this->get(
            $period,
            ['activeUsers', 'screenPageViews'],
            ['pageTitle', 'date'],
            $maxResults,
            [
                OrderBy::dimension('date', true),
            ],
            $offset,
            $dimensionFilter
        );
    }

    /**
     * @param  \NguyenHuy\Analytics\Period  $period
     * @return \Illuminate\Support\Collection<int, array{
     *   date: \Carbon\Carbon,
     *   activeUsers: int,
     *   screenPageViews: int
     * }>
     */
    public function fetchTotalVisitorsAndPageViews(Period $period, int $maxResults = 20, int $offset = 0, FilterExpression $dimensionFilter = null): Collection
    {
        return $this->get(
            $period,
            ['activeUsers', 'screenPageViews'],
            ['date'],
            $maxResults,
            [
                OrderBy::dimension('date', true),
            ],
            $offset,
            $dimensionFilter
        );
    }

    /**
     * @param  \NguyenHuy\Analytics\Period  $period
     * @return \Illuminate\Support\Collection<int, array{
     *   pageTitle: string,
     *   fullPageUrl: string,
     *   screenPageViews: int
     * }>
     */
    public function fetchMostVisitedPages(Period $period, int $maxResults = 20, int $offset = 0, FilterExpression $dimensionFilter = null): Collection
    {
        return $this->get(
            $period,
            ['screenPageViews'],
            ['pageTitle', 'fullPageUrl'],
            $maxResults,
            [
                OrderBy::metric('screenPageViews', true),
            ],
            $offset,
            $dimensionFilter
        );
    }

    /**
     * @param  \NguyenHuy\Analytics\Period  $period
     * @return \Illuminate\Support\Collection<int, array{
     *   pageReferrer: string,
     *   screenPageViews: int
     * }>
     */
    public function fetchTopReferrers(Period $period, int $maxResults = 20, int $offset = 0, FilterExpression $dimensionFilter = null): Collection
    {
        return $this->get(
            $period,
            ['screenPageViews'],
            ['pageReferrer'],
            $maxResults,
            [
                OrderBy::metric('screenPageViews', true),
            ],
            $offset,
            $dimensionFilter
        );
    }

    /**
     * @param  \NguyenHuy\Analytics\Period  $period
     * @return \Illuminate\Support\Collection<int, array{
     *   newVsReturning: string,
     *   activeUsers: int
     * }>
     */
    public function fetchUserTypes(Period $period, FilterExpression $dimensionFilter = null): Collection
    {
        return $this->get(
            $period,
            ['activeUsers'],
            ['newVsReturning'],
            10,
            [],
            0,
            $dimensionFilter
        );
    }

    /**
     * @param  \NguyenHuy\Analytics\Period  $period
     * @return \Illuminate\Support\Collection<int, array{
     *   browser: string,
     *   screenPageViews: int
     * }>
     */
    public function fetchTopBrowsers(Period $period, int $maxResults = 10, int $offset = 0, FilterExpression $dimensionFilter = null): Collection
    {
        return $this->get(
            $period,
            ['screenPageViews'],
            ['browser'],
            $maxResults,
            [
                OrderBy::metric('screenPageViews', true),
            ],
            $offset,
            $dimensionFilter
        );
    }

    public function get(
        Period $period,
        array $metrics,
        array $dimensions = [],
        int $maxResults = 10,
        array $orderBy = [],
        int $offset = 0,
        FilterExpression $dimensionFilter = null,
        bool $keepEmptyRows = false
    ): Collection {
        return $this->client->get(
            $this->propertyId,
            $period,
            $metrics,
            $dimensions,
            $maxResults,
            $orderBy,
            $offset,
            $dimensionFilter,
            $keepEmptyRows
        );
    }
}
