<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Pemasukan;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemasukanCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Kategori::firstOrCreate([
            'name' => 'Beasiswa',
            'type' => 'income',
        ]);
    }

    public function test_mahasiswa_can_create_view_update_and_delete_pemasukan(): void
    {
        $user = Pengguna::factory()->create();

        $this->actingAs($user)
            ->post(route('incomes.store'), [
                'title' => 'Beasiswa semester',
                'amount' => 2500000,
                'category' => 'Beasiswa',
                'transaction_date' => '2026-10-16',
                'description' => 'Beasiswa kampus',
            ])
            ->assertRedirectToRoute('incomes.index');

        $income = Pemasukan::query()->firstOrFail();

        $this->assertSame($user->id, $income->user_id);

        $this->assertDatabaseHas('incomes', [
            'id' => $income->id,
            'title' => 'Beasiswa semester',
        ]);

        $this->get(route('incomes.index'))
            ->assertOk()
            ->assertSee('Beasiswa semester');

        $this->get(route('incomes.edit', $income))
            ->assertOk();

        $this->put(route('incomes.update', $income), [
            'title' => 'Beasiswa diperbarui',
            'amount' => 3000000,
            'category' => 'Beasiswa',
            'transaction_date' => '2026-10-17',
            'description' => null,
        ])->assertRedirectToRoute('incomes.index');

        $this->assertDatabaseHas('incomes', [
            'id' => $income->id,
            'title' => 'Beasiswa diperbarui',
        ]);

        $this->delete(route('incomes.destroy', $income))
            ->assertRedirectToRoute('incomes.index');

        $this->assertDatabaseMissing('incomes', [
            'id' => $income->id,
        ]);
    }

    public function test_mahasiswa_cannot_edit_or_delete_another_users_pemasukan(): void
    {
        $owner = Pengguna::factory()->create();
        $otherUser = Pengguna::factory()->create();

        $income = Pemasukan::create([
            'user_id' => $owner->id,
            'title' => 'Uang saku',
            'amount' => 500000,
            'category' => 'Beasiswa',
            'transaction_date' => '2026-10-16',
        ]);

        $this->actingAs($otherUser)
            ->get(route('incomes.edit', $income))
            ->assertNotFound();

        $this->put(route('incomes.update', $income), [
            'title' => 'Diubah',
            'amount' => 1,
            'category' => 'Beasiswa',
            'transaction_date' => '2026-10-16',
        ])->assertNotFound();

        $this->delete(route('incomes.destroy', $income))
            ->assertNotFound();

        $this->assertDatabaseHas('incomes', [
            'id' => $income->id,
            'title' => 'Uang saku',
        ]);
    }

    public function test_admin_can_view_but_cannot_manage_pemasukan(): void
    {
        $admin = Pengguna::factory()->create([
            'role' => 'admin',
        ]);

        $student = Pengguna::factory()->create();

        $income = Pemasukan::create([
            'user_id' => $student->id,
            'title' => 'Beasiswa mahasiswa',
            'amount' => 500000,
            'category' => 'Beasiswa',
            'transaction_date' => '2026-10-16',
        ]);

        $this->actingAs($admin)
            ->get(route('incomes.index'))
            ->assertOk()
            ->assertSee('Beasiswa mahasiswa');

        $this->get(route('incomes.create'))
            ->assertForbidden();

        $this->get(route('incomes.edit', $income))
            ->assertForbidden();

        $this->delete(route('incomes.destroy', $income))
            ->assertForbidden();

        $this->assertDatabaseHas('incomes', [
            'id' => $income->id,
            'title' => 'Beasiswa mahasiswa',
        ]);
    }
}