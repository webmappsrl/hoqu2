<?php

namespace App\Models;

use App\Enums\HoquJobStatus;
use App\Services\HoquJobService;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaravelJob extends Model
{
  protected $table = 'jobs';
  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  public function hokuJobs()
  {
    return $this->belongsToMany(HoquJob::class);
  }
}
