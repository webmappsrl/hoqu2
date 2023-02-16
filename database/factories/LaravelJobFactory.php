<?php

namespace Database\Factories;

use App\Models\HoquJob;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LaravelJob>
 */
class LaravelJobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $hoquJob = HoquJob::factory()->create();

        return [
            'queue' => $this->faker->word,
            'payload' => $this->faker->text,
            'attempts' => $this->faker->numberBetween(0, 10),
            'reserved_at' => $this->faker->optional()->unixTime,
            'available_at' => $this->faker->unixTime,
            'created_at' => $this->faker->unixTime,
            'hoqu_job_id' => $hoquJob->id,
        ];
    }
}