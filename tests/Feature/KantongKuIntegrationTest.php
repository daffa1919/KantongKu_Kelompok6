<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KantongKuIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')
            ->assertRedirect('/login');
    }

    public function test_admin_can_open_admin_dashboard(): void
    {
        $admin = Pengguna::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard admin');
    }

    public function test_student_cannot_open_admin_dashboard(): void
    {
        $student = Pengguna::factory()->create([
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        $this->actingAs($student)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('Dashboard admin');
    }

    public function test_student_dashboard_is_available(): void
    {
        $student = Pengguna::factory()->create([
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        $this->actingAs($student)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Arus uang harian');
    }
}