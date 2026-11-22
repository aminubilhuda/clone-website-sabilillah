<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(302);
        $this->assertStringContainsString('/login', strval($response->headers->get('Location')));
    }

    public function test_editors_can_manage_content_but_cannot_manage_users(): void
    {
        $editor = User::factory()->create();

        $this->actingAs($editor)->get('/admin/posts')->assertOk();
        $this->actingAs($editor)->get('/admin/users')->assertForbidden();
    }

    public function test_admins_can_manage_users(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/users')->assertOk();
        $this->actingAs($admin)->get('/admin/pengaturan')->assertOk();
    }
}
