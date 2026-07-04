<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Akses penuh ke semua fitur sistem, termasuk pengelolaan pengguna.'
        ]);

        $staffRole = Role::create([
            'name' => 'Staff',
            'slug' => 'staff',
            'description' => 'Akses untuk mengelola data master barang dan transaksi peminjaman.'
        ]);

        $managerRole = Role::create([
            'name' => 'Manager',
            'slug' => 'manager',
            'description' => 'Akses untuk melihat dasbor, laporan, dan statistik inventaris.'
        ]);

        // 2. Seed Users
        $admin = User::create([
            'role_id' => $adminRole->id,
            'name' => 'Admin CampVentory',
            'email' => 'admin@telkomsel.com',
            'password' => Hash::make('password'),
        ]);

        $staff = User::create([
            'role_id' => $staffRole->id,
            'name' => 'Staff Gudang Inlife',
            'email' => 'staff@telkomsel.com',
            'password' => Hash::make('password'),
        ]);

        $manager = User::create([
            'role_id' => $managerRole->id,
            'name' => 'Manager Telkomsel',
            'email' => 'manager@telkomsel.com',
            'password' => Hash::make('password'),
        ]);

        // 3. Seed Categories
        $catTenda = Category::create(['name' => 'Tenda & Shelter', 'description' => 'Berbagai jenis tenda dome, flysheet, dan perlindungan lapangan.']);
        $catSepatu = Category::create(['name' => 'Sepatu & Sandal Gunung', 'description' => 'Alas kaki mendaki gunung dan trail running.']);
        $catTas = Category::create(['name' => 'Tas Carrier & Pack', 'description' => 'Tas pendakian kapasitas besar (carrier) dan tas harian (summit pack).']);
        $catAlatTidur = Category::create(['name' => 'Alat Tidur & Matras', 'description' => 'Sleeping bag, matras foil, matras karet, dan kasur angin.']);
        $catMasak = Category::create(['name' => 'Peralatan Masak & Kompor', 'description' => 'Kompor portable, nesting camping, teko, dan peralatan makan.']);
        $catPenerangan = Category::create(['name' => 'Penerangan & Elektronik', 'description' => 'Headlamp, senter tenda, power bank, dan baterai cadangan.']);
        $catPakaian = Category::create(['name' => 'Pakaian & Proteksi', 'description' => 'Jaket anti-air (goretex), celana outdoor, topi, sarung tangan, dan jas hujan.']);
        $catLain = Category::create(['name' => 'Aksesoris & Keamanan', 'description' => 'Trekking pole, dry bag, emergency blanket, tripod, dan gaiter.']);

        // 4. Seed Products
        $products = [
            [
                'code' => 'TSEL-TND-001',
                'name' => 'Tenda 2P Single Layer',
                'category_id' => $catTenda->id,
                'stock' => 15,
                'storage_location' => 'Rak A-1',
                'condition' => 'Baik',
                'description' => 'Tenda dome ringan kapasitas 2 orang, cocok untuk camping santai.'
            ],
            [
                'code' => 'TSEL-TND-002',
                'name' => 'Tenda 2-3P Double Layer',
                'category_id' => $catTenda->id,
                'stock' => 10,
                'storage_location' => 'Rak A-2',
                'condition' => 'Baru',
                'description' => 'Tenda double layer kapasitas 2-3 orang, tahan badai dan air hujan sedang.'
            ],
            [
                'code' => 'TSEL-TND-003',
                'name' => 'Tenda 3-4P Double Layer',
                'category_id' => $catTenda->id,
                'stock' => 8,
                'storage_location' => 'Rak A-3',
                'condition' => 'Baik',
                'description' => 'Tenda dome double layer untuk 3-4 orang.'
            ],
            [
                'code' => 'TSEL-TND-004',
                'name' => 'Tenda 4-5P Double Layer',
                'category_id' => $catTenda->id,
                'stock' => 6,
                'storage_location' => 'Rak A-4',
                'condition' => 'Baik',
                'description' => 'Tenda dome double layer untuk keluarga kecil.'
            ],
            [
                'code' => 'TSEL-TND-005',
                'name' => 'Tenda 6-7P Double Layer',
                'category_id' => $catTenda->id,
                'stock' => 4,
                'storage_location' => 'Rak A-5',
                'condition' => 'Rusak Ringan',
                'description' => 'Tenda dome kapasitas besar. Ada sedikit sobek di bagian inner.'
            ],
            [
                'code' => 'TSEL-TND-006',
                'name' => 'Tenda Family 4P Glamping',
                'category_id' => $catTenda->id,
                'stock' => 3,
                'storage_location' => 'Lantai Area B-1',
                'condition' => 'Baik',
                'description' => 'Tenda tipe glamping mewah dengan ruang yang luas.'
            ],
            [
                'code' => 'TSEL-TND-007',
                'name' => 'Tenda UL 2P Premium + Footprint',
                'category_id' => $catTenda->id,
                'stock' => 5,
                'storage_location' => 'Rak B-2',
                'condition' => 'Baru',
                'description' => 'Tenda Ultra Light premium kapasitas 2 orang, sudah termasuk footprint.'
            ],
            [
                'code' => 'TSEL-TND-008',
                'name' => 'Tenda UL 4P Premium + Footprint',
                'category_id' => $catTenda->id,
                'stock' => 2, // Low stock warning (< 5)
                'storage_location' => 'Rak B-3',
                'condition' => 'Baru',
                'description' => 'Tenda Ultra Light premium kapasitas 4 orang.'
            ],
            [
                'code' => 'TSEL-SPT-001',
                'name' => 'Sepatu Hiking Waterproof',
                'category_id' => $catSepatu->id,
                'stock' => 12,
                'storage_location' => 'Rak C-1',
                'condition' => 'Baik',
                'description' => 'Sepatu mendaki gunung anti air dengan sol vibram bergerigi.'
            ],
            [
                'code' => 'TSEL-SPT-002',
                'name' => 'Sepatu Trail Run Outdoor',
                'category_id' => $catSepatu->id,
                'stock' => 8,
                'storage_location' => 'Rak C-2',
                'condition' => 'Baik',
                'description' => 'Sepatu lari lintas alam, ringan dan bersirkulasi udara baik.'
            ],
            [
                'code' => 'TSEL-TRK-001',
                'name' => 'Trekking Pole Carbon',
                'category_id' => $catLain->id,
                'stock' => 20,
                'storage_location' => 'Rak D-1',
                'condition' => 'Baik',
                'description' => 'Tongkat daki bahan carbon super ringan dan kuat.'
            ],
            [
                'code' => 'TSEL-SLB-001',
                'name' => 'Sleeping Bag Polar Bulu',
                'category_id' => $catAlatTidur->id,
                'stock' => 30,
                'storage_location' => 'Rak E-1',
                'condition' => 'Baik',
                'description' => 'Kantong tidur hangat dengan lapisan polar bulu di bagian dalam.'
            ],
            [
                'code' => 'TSEL-HDP-001',
                'name' => 'Hydropack Trail Run 5L',
                'category_id' => $catTas->id,
                'stock' => 10,
                'storage_location' => 'Rak D-2',
                'condition' => 'Baru',
                'description' => 'Tas punggung hydro-pack untuk lari trail atau daki cepat.'
            ],
            [
                'code' => 'TSEL-TAS-040',
                'name' => 'Tas Carrier 40L Eiger',
                'category_id' => $catTas->id,
                'stock' => 12,
                'storage_location' => 'Rak F-1',
                'condition' => 'Baik',
                'description' => 'Tas carrier kapasitas medium 40 liter.'
            ],
            [
                'code' => 'TSEL-TAS-060',
                'name' => 'Tas Carrier 60L Arei',
                'category_id' => $catTas->id,
                'stock' => 15,
                'storage_location' => 'Rak F-2',
                'condition' => 'Baik',
                'description' => 'Tas carrier kapasitas standar pendakian 3-4 hari.'
            ],
            [
                'code' => 'TSEL-TAS-080',
                'name' => 'Tas Carrier 80L Consina',
                'category_id' => $catTas->id,
                'stock' => 6,
                'storage_location' => 'Rak F-3',
                'condition' => 'Baik',
                'description' => 'Tas carrier super besar untuk ekspedisi panjang.'
            ],
            [
                'code' => 'TSEL-LMP-001',
                'name' => 'Headlamp LED USB Recharging',
                'category_id' => $catPenerangan->id,
                'stock' => 25,
                'storage_location' => 'Rak G-1',
                'condition' => 'Baik',
                'description' => 'Senter kepala LED rechargeable terang dengan sensor gerak.'
            ],
            [
                'code' => 'TSEL-LMP-002',
                'name' => 'Lampu Lapangan Gantung',
                'category_id' => $catPenerangan->id,
                'stock' => 10,
                'storage_location' => 'Rak G-2',
                'condition' => 'Baik',
                'description' => 'Lampu sorot lapangan untuk menerangi area camp.'
            ],
            [
                'code' => 'TSEL-LMP-003',
                'name' => 'Lampu Tenda Mini',
                'category_id' => $catPenerangan->id,
                'stock' => 15,
                'storage_location' => 'Rak G-3',
                'condition' => 'Baik',
                'description' => 'Lampu tenda gantung mini bertenaga baterai.'
            ],
            [
                'code' => 'TSEL-KMP-001',
                'name' => 'Kompor Gas Portable Besar',
                'category_id' => $catMasak->id,
                'stock' => 8,
                'storage_location' => 'Rak H-1',
                'condition' => 'Baik',
                'description' => 'Kompor portable ukuran besar menggunakan gas kaleng.'
            ],
            [
                'code' => 'TSEL-KMP-002',
                'name' => 'Kompor Mini Camping',
                'category_id' => $catMasak->id,
                'stock' => 12,
                'storage_location' => 'Rak H-2',
                'condition' => 'Baik',
                'description' => 'Kompor camping mini lipat, sangat ringkas.'
            ],
            [
                'code' => 'TSEL-NST-001',
                'name' => 'Nesting Camping Set Isi 4',
                'category_id' => $catMasak->id,
                'stock' => 10,
                'storage_location' => 'Rak H-3',
                'condition' => 'Baik',
                'description' => 'Satu set panci nesting susun isi 4 pcs.'
            ],
            [
                'code' => 'TSEL-FLS-001',
                'name' => 'Flysheet 3x4m Waterproof',
                'category_id' => $catTenda->id,
                'stock' => 18,
                'storage_location' => 'Rak I-1',
                'condition' => 'Baik',
                'description' => 'Lembaran pelindung hujan/panas ukuran 3x4 meter.'
            ],
            [
                'code' => 'TSEL-MTR-001',
                'name' => 'Matras Foil 2x1m',
                'category_id' => $catAlatTidur->id,
                'stock' => 3, // Low stock warning (< 5)
                'storage_location' => 'Rak I-2',
                'condition' => 'Baru',
                'description' => 'Matras berlapis alumunium foil penahan dingin dari tanah.'
            ],
            [
                'code' => 'TSEL-MJL-001',
                'name' => 'Meja Lipat Aluminium Camping',
                'category_id' => $catLain->id,
                'stock' => 10,
                'storage_location' => 'Rak J-1',
                'condition' => 'Baik',
                'description' => 'Meja camping lipat aluminium praktis.'
            ],
            [
                'code' => 'TSEL-KSL-001',
                'name' => 'Kursi Lipat Premium',
                'category_id' => $catLain->id,
                'stock' => 24,
                'storage_location' => 'Rak J-2',
                'condition' => 'Baik',
                'description' => 'Kursi camping lipat bersandaran, nyaman.'
            ],
            [
                'code' => 'TSEL-ACC-002',
                'name' => 'Power Bank Rugged 20000mAh',
                'category_id' => $catPenerangan->id,
                'stock' => 4, // Low stock warning (< 5)
                'storage_location' => 'Rak G-4',
                'condition' => 'Baik',
                'description' => 'Power bank tangguh tahan benturan untuk pendakian.'
            ]
        ];

        $productModels = [];
        foreach ($products as $p) {
            $productModels[] = Product::create($p);
        }

        // 5. Seed Historical Borrowings
        // Let's create data for the last 5 months to show on the dashboard chart.
        $months = [
            ['month' => 2, 'count' => 4],  // Feb
            ['month' => 3, 'count' => 12], // Mar
            ['month' => 4, 'count' => 18], // Apr
            ['month' => 5, 'count' => 28], // May
            ['month' => 6, 'count' => 35], // Jun
            ['month' => 7, 'count' => 15]  // Jul (current month)
        ];

        $borrowers = ['Budi Santoso', 'Siti Rahma', 'Andi Wijaya', 'Dewi Lestari', 'Rian Hidayat', 'Fajar Pratama', 'Putri Ayu', 'Roni Kurniawan', 'Anisa Fitri', 'Yanto', 'Hendra', 'Megawati'];

        foreach ($months as $m) {
            for ($i = 0; $i < $m['count']; $i++) {
                $borrowDate = Carbon::now()->subMonths(Carbon::now()->month - $m['month'])->subDays(rand(1, 28));
                
                // Keep current month active for some borrowings, make past months returned
                $isCurrentMonth = ($m['month'] == Carbon::now()->month);
                $status = ($isCurrentMonth && rand(0, 1) == 0) ? 'Dipinjam' : 'Dikembalikan';
                
                $expectedReturn = (clone $borrowDate)->addDays(rand(2, 7));

                $borrowing = Borrowing::create([
                    'borrower_name' => $borrowers[array_rand($borrowers)],
                    'borrow_date' => $borrowDate,
                    'expected_return_date' => $expectedReturn,
                    'status' => $status,
                    'processed_by' => $staff->id,
                    'created_at' => $borrowDate,
                    'updated_at' => $borrowDate
                ]);

                // Create details
                $itemsCount = rand(1, 3);
                $selectedProducts = array_rand($productModels, $itemsCount);
                if (!is_array($selectedProducts)) {
                    $selectedProducts = [$selectedProducts];
                }

                foreach ($selectedProducts as $prodIdx) {
                    $prod = $productModels[$prodIdx];
                    $qty = rand(1, 2);
                    
                    $returnedAt = ($status === 'Dikembalikan') ? (clone $expectedReturn)->addHours(rand(-4, 12)) : null;

                    BorrowingDetail::create([
                        'borrowing_id' => $borrowing->id,
                        'product_id' => $prod->id,
                        'quantity' => $qty,
                        'returned_at' => $returnedAt,
                        'created_at' => $borrowDate,
                        'updated_at' => $borrowDate
                    ]);

                    // If it is currently "Dipinjam", we deduct the product stock
                    if ($status === 'Dipinjam') {
                        $prod->stock = max(0, $prod->stock - $qty);
                        $prod->save();
                    }
                }
            }
        }
    }
}
