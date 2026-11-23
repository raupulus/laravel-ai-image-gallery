<?php

namespace Database\Factories;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Collection>
 */
class CollectionFactory extends Factory
{
    protected $model = Collection::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'batch_id' => 'batch-'.Str::uuid(),
            'ai' => 'Stable Diffusion',
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'tags' => 'ai,art,gallery',
            'prompt' => fake()->sentence(10),
            'negative_prompt' => 'blurry, lowres',
            'model' => 'SDXL',
            'size' => '1024x576',
            'restore_faces' => false,
        ];
    }
}
