<?php

namespace Tests\Feature;

use App\Models\HeroSlider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSliderCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_edit_update_and_delete_a_slider(): void
    {
        $admin = User::factory()->admin()->create();
        $slider = HeroSlider::factory()->create(['title' => 'Slider Uji']);

        $this->actingAs($admin)
            ->get(route('admin.hero-sliders.edit', $slider))
            ->assertOk()
            ->assertSee('Slider Uji');

        $this->actingAs($admin)
            ->put(route('admin.hero-sliders.update', $slider), [
                'title' => 'Slider Setelah Diubah',
                'link' => 'berita',
                'sort_order' => 3,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.hero-sliders.index'));

        $this->assertSame('Slider Setelah Diubah', $slider->fresh()->title);

        $this->actingAs($admin)
            ->delete(route('admin.hero-sliders.destroy', $slider))
            ->assertRedirect();

        $this->assertDatabaseMissing('hero_sliders', ['id' => $slider->id]);
    }
}
