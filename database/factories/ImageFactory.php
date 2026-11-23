<?php

namespace Database\Factories;

use App\Models\Collection;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'collection_id' => Collection::factory(),
            'thumbnail' => 'collections/1/sample_thumbnail.webp',
            'image' => 'collections/1/sample.webp',
            'seed' => fake()->numberBetween(1, 99999999),
            'order' => 1,
        ];
    }
}
