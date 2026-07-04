<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat role untuk testing
        $this->adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrator']);
        $this->staffRole = Role::create(['name' => 'Staff', 'slug' => 'staff', 'description' => 'Staff Gudang']);
    }

    /**
     * Test: Halaman login bisa diakses.
     */
    public function test_login_page_dapat_ditampilkan(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Test: User bisa login dengan kredensial yang benar.
     */
    public function test_user_bisa_login_dengan_kredensial_benar(): void
    {
        $user = User::factory()->create([
            'role_id' => $this->adminRole->id,
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    /**
     * Test: User tidak bisa login dengan password salah.
     */
    public function test_user_tidak_bisa_login_dengan_password_salah(): void
    {
        $user = User::factory()->create([
            'role_id' => $this->adminRole->id,
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    /**
     * Test: User bisa logout.
     */
    public function test_user_bisa_logout(): void
    {
        $user = User::factory()->create([
            'role_id' => $this->adminRole->id,
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    /**
     * Test: Tamu (guest) tidak bisa mengakses dashboard.
     */
    public function test_tamu_tidak_bisa_akses_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test: User terautentikasi bisa mengakses dashboard.
     */
    public function test_user_terautentikasi_bisa_akses_dashboard(): void
    {
        $user = User::factory()->create([
            'role_id' => $this->adminRole->id,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    // =============================
    // API Auth Tests (Sanctum)
    // =============================

    /**
     * Test: API Login berhasil dan mengembalikan token.
     */
    public function test_api_login_berhasil_mengembalikan_token(): void
    {
        $user = User::factory()->create([
            'role_id' => $this->adminRole->id,
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['user', 'token', 'token_type'],
            ])
            ->assertJson(['success' => true]);
    }

    /**
     * Test: API Login gagal dengan kredensial salah.
     */
    public function test_api_login_gagal_dengan_kredensial_salah(): void
    {
        $user = User::factory()->create([
            'role_id' => $this->adminRole->id,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'salah-banget',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test: API Logout berhasil menghapus token.
     */
    public function test_api_logout_berhasil(): void
    {
        $user = User::factory()->create([
            'role_id' => $this->adminRole->id,
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
