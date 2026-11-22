<?php

namespace Database\Factories;

use App\Models\HeroSlider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HeroSlider>
 */
class HeroSliderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => 'img/hero-1.svg',
            'title' => fake()->sentence(4),
            'link' => 'berita',
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
