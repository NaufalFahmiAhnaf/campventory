<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Category;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $category;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrator']);
        Role::create(['name' => 'Staff', 'slug' => 'staff', 'description' => 'Staff Gudang']);

        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->category = Category::create(['name' => 'Peralatan Masak', 'description' => 'Alat masak gunung']);
        $this->product = Product::create([
            'code' => 'KMP-001',
            'name' => 'Kompor Portable',
            'category_id' => $this->category->id,
            'stock' => 10,
            'storage_location' => 'Rak D1',
            'condition' => 'Baik',
        ]);
    }

    /**
     * Test: Admin bisa melihat halaman daftar peminjaman.
     */
    public function test_admin_bisa_melihat_daftar_peminjaman(): void
    {
        $response = $this->actingAs($this->admin)->get(route('borrowings.index'));
        $response->assertStatus(200);
    }

    /**
     * Test: Admin bisa membuat peminjaman baru dan stok berkurang.
     */
    public function test_admin_bisa_membuat_peminjaman_dan_stok_berkurang(): void
    {
        $response = $this->actingAs($this->admin)->post(route('borrowings.store'), [
            'borrower_name' => 'Andi Pendaki',
            'borrow_date' => '2025-07-01',
            'expected_return_date' => '2025-07-15',
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 3],
            ],
        ]);

        $response->assertRedirect(route('borrowings.index'));
        
        // Stok harus berkurang dari 10 menjadi 7
        $this->assertDatabaseHas('products', ['id' => $this->product->id, 'stock' => 7]);
        $this->assertDatabaseHas('borrowings', ['borrower_name' => 'Andi Pendaki', 'status' => 'Dipinjam']);
    }

    /**
     * Test: Peminjaman ditolak jika stok tidak mencukupi.
     */
    public function test_peminjaman_ditolak_jika_stok_tidak_cukup(): void
    {
        $response = $this->actingAs($this->admin)->post(route('borrowings.store'), [
            'borrower_name' => 'Budi Peminjam',
            'borrow_date' => '2025-07-01',
            'expected_return_date' => '2025-07-15',
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 999],
            ],
        ]);

        // Stok harus tetap 10 (tidak berubah)
        $this->assertDatabaseHas('products', ['id' => $this->product->id, 'stock' => 10]);
    }

    /**
     * Test: Pengembalian barang berhasil dan stok bertambah kembali.
     */
    public function test_pengembalian_barang_mengembalikan_stok(): void
    {
        // Buat peminjaman dulu
        $borrowing = Borrowing::create([
            'borrower_name' => 'Citra Pendaki',
            'borrow_date' => '2025-07-01',
            'expected_return_date' => '2025-07-15',
            'status' => 'Dipinjam',
            'processed_by' => $this->admin->id,
        ]);

        BorrowingDetail::create([
            'borrowing_id' => $borrowing->id,
            'product_id' => $this->product->id,
            'quantity' => 4,
            'returned_at' => null,
        ]);

        // Kurangi stok secara manual (simulasi peminjaman)
        $this->product->decrement('stock', 4);
        $this->assertEquals(6, $this->product->fresh()->stock);

        // Proses pengembalian
        $response = $this->actingAs($this->admin)->post(route('borrowings.return', $borrowing));
        $response->assertRedirect(route('borrowings.index'));

        // Stok harus kembali ke 10
        $this->assertEquals(10, $this->product->fresh()->stock);
        $this->assertDatabaseHas('borrowings', ['id' => $borrowing->id, 'status' => 'Dikembalikan']);
    }

    // =============================
    // API Borrowing Tests
    // =============================

    /**
     * Test: API bisa membuat peminjaman baru.
     */
    public function test_api_bisa_membuat_peminjaman(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/borrowings', [
                'borrower_name' => 'Dewi API',
                'borrow_date' => '2025-08-01',
                'expected_return_date' => '2025-08-15',
                'items' => [
                    ['product_id' => $this->product->id, 'quantity' => 2],
                ],
            ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('products', ['id' => $this->product->id, 'stock' => 8]);
    }

    /**
     * Test: API bisa memproses pengembalian.
     */
    public function test_api_bisa_proses_pengembalian(): void
    {
        // Buat peminjaman melalui API
        $borrowing = Borrowing::create([
            'borrower_name' => 'Eka API',
            'borrow_date' => '2025-08-01',
            'expected_return_date' => '2025-08-15',
            'status' => 'Dipinjam',
            'processed_by' => $this->admin->id,
        ]);

        BorrowingDetail::create([
            'borrowing_id' => $borrowing->id,
            'product_id' => $this->product->id,
            'quantity' => 3,
            'returned_at' => null,
        ]);

        $this->product->decrement('stock', 3);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/borrowings/' . $borrowing->id . '/return');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertEquals(10, $this->product->fresh()->stock);
    }

    /**
     * Test: API menolak pengembalian ganda.
     */
    public function test_api_menolak_pengembalian_ganda(): void
    {
        $borrowing = Borrowing::create([
            'borrower_name' => 'Fani Ganda',
            'borrow_date' => '2025-08-01',
            'expected_return_date' => '2025-08-15',
            'status' => 'Dikembalikan',
            'processed_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/borrowings/' . $borrowing->id . '/return');

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }
}
