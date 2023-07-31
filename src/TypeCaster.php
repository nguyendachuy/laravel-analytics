<?php

namespace NguyenHuy\Analytics;

use Illuminate\Support\Carbon;

class TypeCaster
{
    public function castValue(string $key, string $value)
    {
        switch ($key) {
            case 'date':
                return Carbon::createFromFormat('Ymd', $value);
                break;
            case in_array($key, [
                'visitors', 'pageViews', 'activeUsers', 'newUsers', 'screenPageViews',
                'active1DayUsers', 'active7DayUsers', 'active28DayUsers'
            ]):
                return (int) $value;
                break;
            default:
                return $value;
                break;
        }
        // php 8
        // return match ($key) {
        //     'date' => Carbon::createFromFormat('Ymd', $value),
        //     'visitors', 'pageViews', 'activeUsers', 'newUsers', 'screenPageViews',
        //     'active1DayUsers', 'active7DayUsers', 'active28DayUsers' => (int) $value,
        //     default => $value,
        // };
    }
}
