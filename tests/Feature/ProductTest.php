<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrator']);
        Role::create(['name' => 'Staff', 'slug' => 'staff', 'description' => 'Staff Gudang']);

        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->category = Category::create(['name' => 'Tenda', 'description' => 'Perlengkapan tenda']);
    }

    /**
     * Test: Admin bisa melihat halaman daftar barang.
     */
    public function test_admin_bisa_melihat_halaman_daftar_barang(): void
    {
        $response = $this->actingAs($this->admin)->get(route('products.index'));
        $response->assertStatus(200);
    }

    /**
     * Test: Admin bisa menambahkan barang baru.
     */
    public function test_admin_bisa_menambahkan_barang_baru(): void
    {
        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'code' => 'TND-001',
            'name' => 'Tenda Dome 4 Orang',
            'category_id' => $this->category->id,
            'stock' => 10,
            'storage_location' => 'Rak A1 - Gudang Utama',
            'condition' => 'Baik',
            'description' => 'Tenda dome kapasitas 4 orang.',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['code' => 'TND-001', 'name' => 'Tenda Dome 4 Orang']);
    }

    /**
     * Test: Barang tanpa kode otomatis dibuat dengan kode ter-generate.
     */
    public function test_barang_tanpa_kode_otomatis_dibuat_dengan_kode_generate(): void
    {
        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'Tenda Tanpa Kode',
            'category_id' => $this->category->id,
            'stock' => 5,
            'storage_location' => 'Rak B1',
            'condition' => 'Baik',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Tenda Tanpa Kode',
            'category_id' => $this->category->id,
        ]);

        $product = Product::where('name', 'Tenda Tanpa Kode')->first();
        $this->assertNotNull($product->code);
        $this->assertStringStartsWith('TSEL-TEN-', $product->code);
    }

    /**
     * Test: Admin bisa mengedit data barang.
     */
    public function test_admin_bisa_mengedit_barang(): void
    {
        $product = Product::create([
            'code' => 'SLP-001',
            'name' => 'Sleeping Bag',
            'category_id' => $this->category->id,
            'stock' => 5,
            'storage_location' => 'Rak C1',
            'condition' => 'Baik',
        ]);

        $response = $this->actingAs($this->admin)->put(route('products.update', $product), [
            'code' => 'SLP-001',
            'name' => 'Sleeping Bag Premium',
            'category_id' => $this->category->id,
            'stock' => 8,
            'storage_location' => 'Rak C2',
            'condition' => 'Baik',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Sleeping Bag Premium', 'stock' => 8]);
    }

    /**
     * Test: Admin bisa menghapus barang.
     */
    public function test_admin_bisa_menghapus_barang(): void
    {
        $product = Product::create([
            'code' => 'DEL-001',
            'name' => 'Barang Akan Dihapus',
            'category_id' => $this->category->id,
            'stock' => 1,
            'storage_location' => 'Rak Z',
            'condition' => 'Rusak Berat',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $this->assertSoftDeleted($product);
    }

    /**
     * Test: Admin bisa menghapus permanen barang yang tidak memiliki riwayat.
     */
    public function test_admin_bisa_force_delete_barang_tanpa_riwayat(): void
    {
        $product = Product::create([
            'code' => 'DEL-002',
            'name' => 'Barang Hapus Permanen',
            'category_id' => $this->category->id,
            'stock' => 1,
            'storage_location' => 'Rak Z',
            'condition' => 'Baik',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('products.force-destroy', $product));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    // =============================
    // API Product Tests
    // =============================

    /**
     * Test: API menampilkan daftar barang.
     */
    public function test_api_menampilkan_daftar_barang(): void
    {
        Product::create([
            'code' => 'API-001',
            'name' => 'Tenda API',
            'category_id' => $this->category->id,
            'stock' => 3,
            'storage_location' => 'Rak API',
            'condition' => 'Baik',
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test: API bisa menambah barang baru.
     */
    public function test_api_bisa_menambah_barang(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/products', [
                'code' => 'API-NEW',
                'name' => 'Kompor Portable API',
                'category_id' => $this->category->id,
                'stock' => 5,
                'storage_location' => 'Rak API-2',
                'condition' => 'Baik',
            ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('products', ['code' => 'API-NEW']);
    }

    /**
     * Test: API tanpa autentikasi ditolak (401).
     */
    public function test_api_tanpa_autentikasi_ditolak(): void
    {
        $response = $this->getJson('/api/products');
        $response->assertStatus(401);
    }
}
