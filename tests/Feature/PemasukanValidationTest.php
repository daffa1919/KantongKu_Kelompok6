<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemasukanValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_amount_and_category_are_rejected(): void
    {
        $user = Pengguna::factory()->create();

        $this->actingAs($user)
            ->from(route('incomes.create'))
            ->post(route('incomes.store'), [
                'title' => 'Uang saku',
                'amount' => 0,
                'category' => 'Kategori tidak dikenal',
                'transaction_date' => '2026-10-16',
            ])
            ->assertRedirect(route('incomes.create'))
            ->assertSessionHasErrors(['amount', 'category']);
    }
}