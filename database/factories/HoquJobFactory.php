<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Wm\WmPackage\Enums\JobStatus;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HoquJob>
 */
class HoquJobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'status' => collect(JobStatus::cases())->random(),
            'input' => $this->faker->word(),
            'output' => $this->faker->word(),
            'caller_id' => User::inRandomOrder()->first()->id,
            'processor_id' => User::inRandomOrder()->first()->id,

        ];
    }
}
