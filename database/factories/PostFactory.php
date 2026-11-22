<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => null,
            'type' => Post::TYPE_BERITA,
            'jurusan' => null,
            'title' => fake()->sentence(6),
            'slug' => fake()->unique()->slug(6),
            'excerpt' => fake()->sentence(12),
            'content' => fake()->paragraphs(3, true),
            'cover_image' => 'img/berita-1.svg',
            'document_path' => null,
            'starts_at' => null,
            'ends_at' => null,
            'location' => null,
            'user_id' => null,
            'status' => 'published',
            'published_at' => now()->subDay(),
            'is_featured' => false,
            'views' => 0,
        ];
    }
}
