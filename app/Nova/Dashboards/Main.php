<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\HoquJobsCount;
use App\Nova\Metrics\HoquJobsStatusCount;
use App\Nova\Metrics\LaravelJobsCount;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new HoquJobsCount,
            new HoquJobsStatusCount,
            //new LaravelJobsCount,
            //TODO: add this metric when LaravelJob model is fixed
        ];
    }
}
