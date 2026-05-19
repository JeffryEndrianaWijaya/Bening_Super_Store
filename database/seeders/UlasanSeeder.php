<?php
 
namespace Database\Seeders;
 
use App\Models\User;
use App\Models\Produk;
use App\Models\Ulasan;
use Illuminate\Database\Seeder;
 
class UlasanSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggan = User::where('role', 'pelanggan')->first();
        if (!$pelanggan) {
            $pelanggan = User::create([
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'pelanggan',
                'status' => true,
            ]);
        }
 
        $pelanggan2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
            'status' => true,
        ]);
 
        $produks = Produk::all();
        if ($produks->isEmpty()) {
            return;
        }
 
        $comments = [
            5 => ['Barang sangat bagus, pengiriman cepat!', 'Kualitas premium asli, recommended seller!'],
            4 => ['Produk oke, berfungsi dengan baik.', 'Harga bersahabat, kualitas mantap.'],
            3 => ['Lumayan lah untuk harga segini.', 'Kualitas standar, respon penjual biasa saja.'],
            2 => ['Kurang puas, barang agak lecet.', 'Pengiriman lambat sekali.'],
            1 => ['Kecewa berat, barang rusak saat sampai.', 'Tidak sesuai deskripsi!'],
        ];
 
        foreach ($produks as $produk) {
            // Pelanggan 1
            $rating1 = rand(3, 5);
            Ulasan::create([
                'id_user' => $pelanggan->id,
                'id_produk' => $produk->id_produk,
                'rating' => $rating1,
                'komentar' => $comments[$rating1][array_rand($comments[$rating1])],
            ]);
 
            // Pelanggan 2 (kadang-kadang review)
            if (rand(0, 1)) {
                $rating2 = rand(1, 4);
                Ulasan::create([
                    'id_user' => $pelanggan2->id,
                    'id_produk' => $produk->id_produk,
                    'rating' => $rating2,
                    'komentar' => $comments[$rating2][array_rand($comments[$rating2])],
                ]);
            }
        }
    }
}
