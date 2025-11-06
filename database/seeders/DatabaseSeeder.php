<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ======= RESET DATA UNTUK DEV =======
        Schema::disableForeignKeyConstraints();
        Product::truncate();
        Category::truncate();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        // ======= USER UTAMA =======
        User::create([
            'name' => 'Admin',
            'email' => 'admin@pos.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@pos.com',
            'password' => Hash::make('password'),
        ]);

        // ======= USER DUMMY DENGAN FAKER =======
        // (pastikan file UserSeeder.php sudah ada)
        $this->call(UserSeeder::class);

        // ======= KATEGORI =======
        $this->call([ CategorySeeder::class ]);

        // ======= PRODUK PER KATEGORI (AMAN DENGAN RELASI & TRANSACTION) =======
        DB::beginTransaction();

        try {
            // Kelompokkan produk per NAMA kategori.
            // Penting: Nama HARUS persis dengan yang dibuat di CategorySeeder.
            $byCategory = [
                'Makanan' => [
                    ['code'=>'PRD-001','name'=>'Nasi Goreng','description'=>'Nasi goreng spesial dengan telur','price'=>25000,'stock'=>50,'is_active'=>true],
                    ['code'=>'PRD-002','name'=>'Mie Ayam','description'=>'Mie ayam dengan bakso','price'=>20000,'stock'=>40,'is_active'=>true],
                    ['code'=>'PRD-003','name'=>'Ayam Goreng','description'=>'Ayam goreng crispy','price'=>30000,'stock'=>30,'is_active'=>true],
                    ['code'=>'PRD-004','name'=>'Sate Ayam','description'=>'Sate ayam 10 tusuk dengan bumbu kacang','price'=>28000,'stock'=>35,'is_active'=>true],
                ],
                'Minuman' => [
                    ['code'=>'PRD-005','name'=>'Es Teh Manis','description'=>'Es teh manis segar','price'=>5000,'stock'=>100,'is_active'=>true],
                    ['code'=>'PRD-006','name'=>'Kopi Hitam','description'=>'Kopi hitam tanpa gula','price'=>8000,'stock'=>80,'is_active'=>true],
                    ['code'=>'PRD-007','name'=>'Jus Jeruk','description'=>'Jus jeruk segar asli','price'=>12000,'stock'=>60,'is_active'=>true],
                    ['code'=>'PRD-008','name'=>'Air Mineral','description'=>'Air mineral kemasan 600ml','price'=>3000,'stock'=>200,'is_active'=>true],
                ],
                'Snack' => [
                    ['code'=>'PRD-009','name'=>'Keripik Kentang','description'=>'Keripik kentang rasa original','price'=>15000,'stock'=>75,'is_active'=>true],
                    ['code'=>'PRD-010','name'=>'Coklat Batangan','description'=>'Coklat batangan premium','price'=>10000,'stock'=>90,'is_active'=>true],
                    ['code'=>'PRD-011','name'=>'Biskuit','description'=>'Biskuit krim vanilla','price'=>8000,'stock'=>100,'is_active'=>true],
                    ['code'=>'PRD-012','name'=>'Kacang Goreng','description'=>'Kacang goreng renyah pedas','price'=>12000,'stock'=>85,'is_active'=>true],
                ],
                'Elektronik' => [
                    ['code'=>'PRD-013','name'=>'Kabel USB Type-C','description'=>'Kabel USB Type-C 1 meter','price'=>25000,'stock'=>45,'is_active'=>true],
                    ['code'=>'PRD-014','name'=>'Earphone','description'=>'Earphone dengan mic','price'=>50000,'stock'=>30,'is_active'=>true],
                    ['code'=>'PRD-015','name'=>'Power Bank','description'=>'Power bank 10000mAh','price'=>150000,'stock'=>20,'is_active'=>true],
                ],
                'Alat Tulis' => [
                    ['code'=>'PRD-016','name'=>'Pulpen','description'=>'Pulpen tinta hitam','price'=>3000,'stock'=>150,'is_active'=>true],
                    ['code'=>'PRD-017','name'=>'Buku Tulis','description'=>'Buku tulis 40 lembar','price'=>5000,'stock'=>100,'is_active'=>true],
                    ['code'=>'PRD-018','name'=>'Penghapus','description'=>'Penghapus karet putih','price'=>2000,'stock'=>200,'is_active'=>true],
                    ['code'=>'PRD-019','name'=>'Pensil 2B','description'=>'Pensil 2B per batang','price'=>2500,'stock'=>180,'is_active'=>true],
                ],
                'Pakaian' => [
                    ['code'=>'PRD-020','name'=>'Kaos Polos','description'=>'Kaos polos cotton combed','price'=>45000,'stock'=>60,'is_active'=>true],
                    ['code'=>'PRD-021','name'=>'Kemeja Formal','description'=>'Kemeja formal lengan panjang','price'=>120000,'stock'=>35,'is_active'=>true],
                    ['code'=>'PRD-022','name'=>'Celana Jeans','description'=>'Celana jeans denim premium','price'=>200000,'stock'=>40,'is_active'=>true],
                ],
                'Sepatu' => [
                    ['code'=>'PRD-023','name'=>'Sneakers Casual','description'=>'Sepatu sneakers casual nyaman','price'=>250000,'stock'=>25,'is_active'=>true],
                    ['code'=>'PRD-024','name'=>'Sandal Jepit','description'=>'Sandal jepit karet anti slip','price'=>35000,'stock'=>80,'is_active'=>true],
                ],
                'Tas' => [
                    ['code'=>'PRD-025','name'=>'Tas Ransel','description'=>'Tas ransel laptop 14 inch','price'=>180000,'stock'=>30,'is_active'=>true],
                    ['code'=>'PRD-026','name'=>'Dompet Kulit','description'=>'Dompet kulit sintetis premium','price'=>75000,'stock'=>50,'is_active'=>true],
                ],
                'Aksesori' => [
                    ['code'=>'PRD-027','name'=>'Jam Tangan Digital','description'=>'Jam tangan digital sporty','price'=>150000,'stock'=>20,'is_active'=>true],
                    ['code'=>'PRD-028','name'=>'Kacamata Hitam','description'=>'Kacamata hitam UV protection','price'=>95000,'stock'=>35,'is_active'=>true],
                    ['code'=>'PRD-029','name'=>'Ikat Pinggang','description'=>'Ikat pinggang kulit casual','price'=>65000,'stock'=>45,'is_active'=>true],
                ],
                'Kosmetik' => [
                    ['code'=>'PRD-030','name'=>'Lipstik Matte','description'=>'Lipstik matte tahan lama','price'=>65000,'stock'=>55,'is_active'=>true],
                    ['code'=>'PRD-031','name'=>'Foundation','description'=>'Foundation liquid natural','price'=>120000,'stock'=>40,'is_active'=>true],
                ],
                'Perawatan Tubuh' => [
                    ['code'=>'PRD-032','name'=>'Sabun Mandi Cair','description'=>'Sabun mandi cair antibakteri','price'=>25000,'stock'=>90,'is_active'=>true],
                    ['code'=>'PRD-033','name'=>'Shampoo Anti Ketombe','description'=>'Shampoo mengatasi ketombe','price'=>30000,'stock'=>70,'is_active'=>true],
                    ['code'=>'PRD-034','name'=>'Body Lotion','description'=>'Body lotion melembabkan kulit','price'=>35000,'stock'=>65,'is_active'=>true],
                ],
                'Kesehatan' => [
                    ['code'=>'PRD-035','name'=>'Masker Medis','description'=>'Masker medis 3 ply isi 50','price'=>40000,'stock'=>100,'is_active'=>true],
                    ['code'=>'PRD-036','name'=>'Hand Sanitizer','description'=>'Hand sanitizer 100ml','price'=>15000,'stock'=>120,'is_active'=>true],
                ],
                'Obat-obatan' => [
                    ['code'=>'PRD-037','name'=>'Vitamin C','description'=>'Vitamin C 1000mg isi 30 tablet','price'=>45000,'stock'=>65,'is_active'=>true],
                    ['code'=>'PRD-038','name'=>'Paracetamol','description'=>'Paracetamol 500mg strip 10 tablet','price'=>8000,'stock'=>150,'is_active'=>true],
                ],
                'Peralatan Rumah' => [
                    ['code'=>'PRD-039','name'=>'Sapu Lidi','description'=>'Sapu lidi natural','price'=>15000,'stock'=>40,'is_active'=>true],
                    ['code'=>'PRD-040','name'=>'Pel Lantai','description'=>'Pel lantai microfiber','price'=>35000,'stock'=>30,'is_active'=>true],
                ],
                'Peralatan Dapur' => [
                    ['code'=>'PRD-041','name'=>'Wajan Teflon','description'=>'Wajan teflon anti lengket 28cm','price'=>125000,'stock'=>25,'is_active'=>true],
                    ['code'=>'PRD-042','name'=>'Set Pisau Dapur','description'=>'Set pisau dapur stainless steel','price'=>85000,'stock'=>35,'is_active'=>true],
                ],
                'Mainan Anak' => [
                    ['code'=>'PRD-043','name'=>'Mainan Mobil Remote','description'=>'Mobil remote control mini','price'=>95000,'stock'=>30,'is_active'=>true],
                    ['code'=>'PRD-044','name'=>'Lego Building Blocks','description'=>'Lego blocks 100 pcs','price'=>120000,'stock'=>25,'is_active'=>true],
                ],
                'Buku' => [
                    ['code'=>'PRD-045','name'=>'Novel Bestseller','description'=>'Novel fiksi bestseller','price'=>85000,'stock'=>40,'is_active'=>true],
                ],
                'Gaming' => [
                    ['code'=>'PRD-046','name'=>'Gaming Mouse','description'=>'Gaming mouse RGB 7 button','price'=>175000,'stock'=>25,'is_active'=>true],
                    ['code'=>'PRD-047','name'=>'Gaming Keyboard','description'=>'Gaming keyboard mechanical RGB','price'=>350000,'stock'=>15,'is_active'=>true],
                ],
                'Komputer' => [
                    ['code'=>'PRD-048','name'=>'Mouse Wireless','description'=>'Mouse wireless 2.4GHz','price'=>65000,'stock'=>50,'is_active'=>true],
                ],
                'Handphone' => [
                    ['code'=>'PRD-049','name'=>'Casing HP','description'=>'Casing HP silikon shockproof','price'=>35000,'stock'=>80,'is_active'=>true],
                    ['code'=>'PRD-050','name'=>'Tempered Glass','description'=>'Tempered glass screen protector','price'=>25000,'stock'=>100,'is_active'=>true],
                ],
            ];

            foreach ($byCategory as $categoryName => $items) {
                $category = Category::where('name', $categoryName)->first();

                if (!$category) {
                    throw new \RuntimeException("Kategori '{$categoryName}' tidak ditemukan. Samakan dengan CategorySeeder.");
                }

                foreach ($items as $p) {
                    // Insert via RELASI agar category_id selalu valid
                    $category->products()->create([
                        'code'        => $p['code'],
                        'name'        => $p['name'],
                        'description' => $p['description'],
                        'price'       => $p['price'],
                        'stock'       => $p['stock'],
                        'is_active'   => $p['is_active'],
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command?->error("Gagal seeding produk: " . $e->getMessage());
            throw $e;
        }

        $this->call(UserSeeder::class);
        $this->command?->info('Database seeded successfully!');
        $this->command?->info('Total Categories: ' . Category::count());
        $this->command?->info('Total Products: ' . Product::count());
    }
}
