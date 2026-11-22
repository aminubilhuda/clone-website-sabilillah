<?php

namespace Tests\Feature;

use App\Models\PpdbApplicant;
use App\Models\PpdbFee;
use App\Models\PpdbRequirement;
use App\Models\PpdbWave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPpdbCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    public function test_ppdb_waves_crud(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/ppdb-waves', [
            'label' => 'Gelombang Uji',
            'mulai' => '2027-01-01',
            'selesai' => '2027-02-28',
            'biaya' => 0,
            'benefit' => 'Diskon uji',
            'sort_order' => 9,
            'is_active' => '1',
        ])->assertRedirect(route('admin.ppdb-waves.index'));

        $wave = PpdbWave::query()->where('label', 'Gelombang Uji')->firstOrFail();

        $this->actingAs($admin)->put("/admin/ppdb-waves/{$wave->id}", [
            'label' => 'Gelombang Diubah',
            'mulai' => '2027-01-01',
            'selesai' => '2027-02-28',
            'biaya' => 10000,
            'is_active' => '1',
        ])->assertRedirect(route('admin.ppdb-waves.index'));

        $this->assertSame('Gelombang Diubah', $wave->fresh()->label);

        $this->actingAs($admin)->delete("/admin/ppdb-waves/{$wave->id}")->assertRedirect();
        $this->assertDatabaseMissing('ppdb_waves', ['id' => $wave->id]);
    }

    public function test_ppdb_requirements_crud(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/ppdb-requirements', [
            'type' => 'umum',
            'content' => 'Syarat uji',
            'sort_order' => 1,
        ])->assertRedirect();

        $req = PpdbRequirement::query()->where('content', 'Syarat uji')->firstOrFail();

        $this->actingAs($admin)->put("/admin/ppdb-requirements/{$req->id}", [
            'content' => 'Syarat diubah',
            'sort_order' => 2,
        ])->assertRedirect();

        $this->assertSame('Syarat diubah', $req->fresh()->content);

        $this->actingAs($admin)->delete("/admin/ppdb-requirements/{$req->id}")->assertRedirect();
        $this->assertDatabaseMissing('ppdb_requirements', ['id' => $req->id]);
    }

    public function test_ppdb_public_form_stores_applicant_and_redirects_to_whatsapp(): void
    {
        $this->post('/ppdb', [
            'name' => 'Calon Siswa Uji',
            'nisn' => '1234567890',
            'asal_sekolah' => 'SMP Uji',
            'jurusan' => 'tkj',
            'parent_name' => 'Orang Tua Uji',
            'whatsapp' => '08123456789',
            'alamat' => 'Jl. Uji No. 1',
        ])->assertRedirect();

        $applicant = PpdbApplicant::query()->where('name', 'Calon Siswa Uji')->firstOrFail();
        $this->assertSame('baru', $applicant->status);
        $this->assertSame('tkj', $applicant->jurusan);
    }

    public function test_ppdb_fees_crud(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/ppdb-fees', [
            'label' => 'Biaya Uji',
            'category' => 'SEKALI',
            'description' => 'Deskripsi uji.',
            'nominal' => 123000,
            'sort_order' => 9,
            'is_active' => '1',
        ])->assertRedirect(route('admin.ppdb-fees.index'));

        $fee = PpdbFee::query()->where('label', 'Biaya Uji')->firstOrFail();

        $this->actingAs($admin)->put("/admin/ppdb-fees/{$fee->id}", [
            'label' => 'Biaya Diubah',
            'category' => 'TAHUNAN',
            'description' => 'Deskripsi diubah.',
            'nominal' => 456000,
            'sort_order' => 3,
            'is_active' => '1',
        ])->assertRedirect(route('admin.ppdb-fees.index'));

        $this->assertSame('Biaya Diubah', $fee->fresh()->label);
        $this->assertSame(456000, $fee->fresh()->nominal);

        $this->actingAs($admin)->delete("/admin/ppdb-fees/{$fee->id}")->assertRedirect();
        $this->assertDatabaseMissing('ppdb_fees', ['id' => $fee->id]);
    }

    public function test_ppdb_applicant_status_update_and_delete(): void
    {
        $admin = $this->admin();

        $applicant = PpdbApplicant::query()->create([
            'name' => 'Calon Siswa Uji',
            'asal_sekolah' => 'SMP Uji',
            'jurusan' => 'tkj',
            'parent_name' => 'Orang Tua Uji',
            'whatsapp' => '08123456789',
            'alamat' => 'Jl. Uji No. 1',
            'status' => 'baru',
        ]);

        $this->actingAs($admin)->get(route('admin.ppdb-applicants.show', $applicant))->assertOk();

        $this->actingAs($admin)->put(route('admin.ppdb-applicants.update', $applicant), [
            'status' => 'diterima',
            'notes' => 'Lulus seleksi.',
        ])->assertRedirect();

        $this->assertSame('diterima', $applicant->fresh()->status);
        $this->assertSame('Lulus seleksi.', $applicant->fresh()->notes);

        $this->actingAs($admin)->delete(route('admin.ppdb-applicants.destroy', $applicant))->assertRedirect();
        $this->assertDatabaseMissing('ppdb_applicants', ['id' => $applicant->id]);
    }
}
