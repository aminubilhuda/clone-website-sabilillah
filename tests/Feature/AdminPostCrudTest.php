<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPostCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_publish_delete_and_restore_a_post(): void
    {
        $admin = User::factory()->admin()->create();

        Page::factory()->create([
            'slug' => 'tkj',
            'title' => 'Teknik Komputer dan Jaringan',
            'group' => 'jurusan',
        ]);

        $this->actingAs($admin)
            ->post('/admin/posts', [
                'type' => 'berita',
                'title' => 'Berita Uji Fitur',
                'excerpt' => 'Ringkasan berita uji.',
                'content' => 'Isi berita uji coba.',
                'status' => 'published',
                'jurusan' => 'tkj',
            ])
            ->assertRedirect(route('admin.posts.index', ['type' => 'berita']));

        $post = Post::query()->where('title', 'Berita Uji Fitur')->firstOrFail();

        $this->assertSame('tkj', $post->jurusan);
        $this->assertSame('published', $post->status);

        // Tarik dari terbit.
        $this->actingAs($admin)
            ->post(route('admin.posts.publish', $post))
            ->assertRedirect();

        $this->assertSame('draft', $post->fresh()->status);

        // Hapus sementara lalu pulihkan.
        $this->actingAs($admin)
            ->delete(route('admin.posts.destroy', $post))
            ->assertRedirect();

        $this->assertSoftDeleted('posts', ['id' => $post->id]);

        $this->actingAs($admin)
            ->post(route('admin.posts.restore', $post->id))
            ->assertRedirect();

        $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
    }

    public function test_jurusan_must_be_a_registered_competency(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/posts', [
                'type' => 'berita',
                'title' => 'Berita Jurusan Tidak Ada',
                'status' => 'published',
                'jurusan' => 'tidak-ada',
            ])
            ->assertSessionHasErrors('jurusan');
    }
}
