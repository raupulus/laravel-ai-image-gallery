<?php

namespace Database\Factories;

use App\Models\CollectionRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CollectionRole>
 */
class CollectionRoleFactory extends Factory
{
    protected $model = CollectionRole::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'slug' => Str::slug($name),
            'name' => ucwords($name),
            'description' => fake()->sentence(),
        ];
    }
}
