<?php

namespace App\Listeners;

use App\Models\HoquJob;
use App\Models\LaravelJob;
use App\Services\HoquJobService;
use Illuminate\Queue\Events\JobQueued;

/**
 * JobEventsSubscriber class
 *
 * Listener for some Queue/Job events
 *
 * https://laravel.com/docs/9.x/events#writing-event-subscribers
 */
class JobEventsSubscriber
{
    /**
     * The service that handle jobs
     *
     * @var \App\Services\HoquJobService
     */
    protected $service;

    function __construct(HoquJobService $service)
    {
        $this->service = $service;
    }

    /**
     * If the job has property named "hoqu_job" attach this job to HoquJob model
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
