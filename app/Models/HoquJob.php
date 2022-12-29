<?php

namespace App\Models;

use App\Services\HoquJobService;
use App\Enums\HoquJobStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoquJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'input',
        'output',
        'caller_id',
        'processor_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => HoquJobStatus::class,
    ];

    /**
     * Undocumented variable
     *
     * @var \App\Services\HoquJobService
     */
    protected $hoquJobService;

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->hoquJobService = app()->make(HoquJobService::class);
    }

    public function caller_id()
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    public function processor_id()
    {
        return $this->belongsTo(User::class, 'processor_id');
    }

    public function jobs()
    {
        return $this->belongsToMany(LaravelJob::class, 'hoqu_job_job', 'hoqu_job_id', 'job_id');
    }


    /**
     *
     * SERVICES
     *
     **/

    public function addStoreJob()
    {
        $this->hoquJobService->addStoreJob($this, 'test', '{}');
    }
}
