<?php

namespace App\Listeners;

use App\Models\HoquJob;
use App\Models\LaravelJob;
use App\Services\HoquJobService;
use Illuminate\Queue\Events\JobQueued;


class JobEventsSubscriber
{
    /**
     * Undocumented variable
     *
     * @var \App\Services\HoquJobService
     */
    protected $service;

    function __construct(HoquJobService $service)
    {
        $this->service = $service;
    }

    /**
     * Undocumented function
     *
     * @param \Illuminate\Queue\Events\JobQueued $event
     * @return void
     */
    function attachLaravelJobToHoquJob(JobQueued $event)
    {
        $hoquJob = method_exists($event->job, 'getHoquJob') ? $event->job->getHoquJob() : false;
        if ($hoquJob !== false) {
            $laravelJob = LaravelJob::findOrFail($event->id);
            $this->service->addJobToHoquJob($laravelJob, $hoquJob);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        return [
            JobQueued::class => 'attachLaravelJobToHoquJob'
        ];
    }
}
