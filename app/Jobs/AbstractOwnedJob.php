<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Services\HoquJobService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\Middleware\HoquSubJobMiddleware;
use Illuminate\Contracts\Queue\ShouldBeUnique;

abstract class AbstractOwnedJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Undocumented variable
   *
   * @var \App\Services\HoquJobService
   */
  protected $service;


  /**
   * Undocumented variable
   *
   * @var \App\Models\HoquJob
   */
  protected $hoqu_job;



  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($hoqu_job)
  {
    $this->hoqu_job = $hoqu_job;
    /**
     * @var \App\Services\HoquJobService
     */
    $this->service = app()->make(HoquJobService::class);
  }


  /**
   * Undocumented function
   *
   * @return \App\Models\HoquJob
   */
  public function getHoquJob()
  {
    return $this->hoqu_job;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  abstract public function handle();
}
