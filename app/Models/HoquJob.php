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
        'processor_id',
        'name'
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

    public function caller()
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processor_id');
    }

    public function laravelJobs()
    {
        return $this->hasMany(LaravelJob::class);
    }
}
