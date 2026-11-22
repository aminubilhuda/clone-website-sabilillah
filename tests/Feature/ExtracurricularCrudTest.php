<?php

namespace Tests\Feature;

use App\Models\Extracurricular;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExtracurricularCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_extracurriculars(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/extracurriculars', [
            'name' => 'Ekstrakurikuler Uji',
            'description' => 'Deskripsi uji.',
            'icon' => 'fa-robot',
            'sort_order' => 5,
            'is_active' => '1',
        ])->assertRedirect(route('admin.extracurriculars.index'));

        $item = Extracurricular::query()->where('name', 'Ekstrakurikuler Uji')->firstOrFail();
        $this->assertSame('fa-robot', $item->iconClass());

        $this->actingAs($admin)->put("/admin/extracurriculars/{$item->id}", [
            'name' => 'Ekstrakurikuler Diubah',
            'description' => 'Deskripsi diubah.',
            'icon' => 'fa-music',
            'sort_order' => 1,
            'is_active' => '1',
        ])->assertRedirect(route('admin.extracurriculars.index'));

        $this->assertSame('Ekstrakurikuler Diubah', $item->fresh()->name);

        $this->actingAs($admin)->delete("/admin/extracurriculars/{$item->id}")->assertRedirect();
        $this->assertDatabaseMissing('extracurriculars', ['id' => $item->id]);
    }

    public function test_public_extracurricular_page_renders_items(): void
    {
        Extracurricular::query()->create([
            'name' => 'Robotik Uji',
            'description' => 'Deskripsi robotik.',
            'icon' => 'fa-robot',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->get('/ekstrakurikuler')
            ->assertOk()
            ->assertSee('Robotik Uji')
            ->assertSee('fa-robot', false);
    }
}
