<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use App\Models\TargetTabungan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TargetTabunganTest extends TestCase
{
    use RefreshDatabase;

    public function test_mahasiswa_can_create_update_and_delete_own_target(): void
    {
        $mahasiswa = Pengguna::factory()->create([
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        $this->actingAs($mahasiswa)
            ->post(route('tabungan.store'), [
                'nama_target' => 'Beli laptop',
                'target_nominal' => 10000000,
                'terkumpul' => 1000000,
                'deskripsi' => 'Laptop untuk kuliah',
            ])
            ->assertRedirectToRoute('tabungan.index');

        $target = TargetTabungan::where('nama_target', 'Beli laptop')->firstOrFail();

        $this->assertSame($mahasiswa->id, $target->user_id);

        $this->put(route('tabungan.update', $target), [
            'nama_target' => 'Laptop kuliah',
            'target_nominal' => 12000000,
            'terkumpul' => 2000000,
        ])->assertRedirectToRoute('tabungan.index');

        $this->assertDatabaseHas('target_tabungans', [
            'id' => $target->id,
            'nama_target' => 'Laptop kuliah',
        ]);

        $this->delete(route('tabungan.destroy', $target))
            ->assertRedirectToRoute('tabungan.index');

        $this->assertDatabaseMissing('target_tabungans', ['id' => $target->id]);
    }

    public function test_mahasiswa_cannot_access_another_users_target(): void
    {
        $owner = Pengguna::factory()->create(['role' => 'mahasiswa']);
        $other = Pengguna::factory()->create(['role' => 'mahasiswa']);

        $target = TargetTabungan::create([
            'user_id' => $owner->id,
            'nama_target' => 'Beli laptop',
            'target_nominal' => 10000000,
            'terkumpul' => 0,
        ]);

        $this->actingAs($other)
            ->get(route('tabungan.edit', $target))
            ->assertNotFound();

        $this->put(route('tabungan.update', $target), [
            'nama_target' => 'Target diubah',
            'target_nominal' => 10000000,
            'terkumpul' => 0,
        ])->assertNotFound();

        $this->delete(route('tabungan.destroy', $target))
            ->assertNotFound();
    }

    public function test_target_nominal_must_fit_database_capacity_and_collected_amount_must_not_exceed_target(): void
    {
        $mahasiswa = Pengguna::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($mahasiswa)
            ->from(route('tabungan.create'))
            ->post(route('tabungan.store'), [
                'nama_target' => 'Target tidak valid',
                'target_nominal' => 100000,
                'terkumpul' => 100001,
            ])
            ->assertSessionHasErrors('terkumpul');

        $this->from(route('tabungan.create'))
            ->post(route('tabungan.store'), [
                'nama_target' => 'Target terlalu besar',
                'target_nominal' => 10000000000000,
                'terkumpul' => 0,
            ])
            ->assertSessionHasErrors('target_nominal');
    }
}
