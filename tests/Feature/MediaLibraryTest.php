<?php

namespace Tests\Feature;

use App\Models\HeroSlider;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaLibraryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_media_library(): void
    {
        $this->getJson(route('admin.media.index'))->assertUnauthorized();
    }

    public function test_staff_can_list_uploaded_images_and_reuse_without_duplicating(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $file = UploadedFile::fake()->image('banner-sekolah.png', 400, 200);

        $this->actingAs($admin)
            ->post(route('admin.hero-sliders.store'), [
                'title' => 'Slider Pertama',
                'image' => $file,
            ])
            ->assertRedirect(route('admin.hero-sliders.index'));

        $slider = HeroSlider::query()->firstOrFail();
        $storedPath = $slider->image;
        Storage::disk('public')->assertExists($storedPath);

        $this->actingAs($admin)
            ->getJson(route('admin.media.index'))
            ->assertOk()
            ->assertJsonFragment(['path' => $storedPath]);

        // Reuse existing image for a new post without uploading a duplicate file
        $this->actingAs($admin)
            ->post(route('admin.posts.store'), [
                'type' => 'berita',
                'title' => 'Berita Pakai Media Lama',
                'content' => 'Konten berita.',
                'status' => 'published',
                'cover_image_existing' => $storedPath,
            ])
            ->assertRedirect();

        $post = Post::query()->where('title', 'Berita Pakai Media Lama')->firstOrFail();
        $this->assertSame($storedPath, $post->cover_image);

        // Deleting the slider must NOT delete the shared file while the post still uses it
        $this->actingAs($admin)
            ->delete(route('admin.hero-sliders.destroy', $slider))
            ->assertRedirect();

        Storage::disk('public')->assertExists($storedPath);
    }

    public function test_invalid_or_traversal_existing_media_paths_are_ignored(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.posts.store'), [
                'type' => 'berita',
                'title' => 'Berita Path Tidak Valid',
                'content' => 'Konten berita.',
                'status' => 'published',
                'cover_image_existing' => '../.env',
            ])
            ->assertRedirect();

        $post = Post::query()->where('title', 'Berita Path Tidak Valid')->firstOrFail();
        $this->assertNull($post->cover_image);
    }

    public function test_staff_can_manage_upload_and_delete_media_in_media_manager(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.media.manage'))
            ->assertOk()
            ->assertSee('Kelola Media');

        $this->actingAs($admin)
            ->post(route('admin.media.store'), [
                'file' => UploadedFile::fake()->image('galeri-baru.png', 300, 300),
            ])
            ->assertRedirect(route('admin.media.manage'));

        $files = Storage::disk('public')->allFiles('uploads');
        $this->assertCount(1, $files);
        $path = $files[0];

        $this->assertContains($path, array_column(\App\Support\Uploads::listImages(), 'path'));

        $this->actingAs($admin)
            ->delete(route('admin.media.destroy'), ['path' => $path])
            ->assertRedirect(route('admin.media.manage'));

        Storage::disk('public')->assertMissing($path);
    }
}
