<?php
namespace Database\Seeders;

use App\Models\{Partner,Report,User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun demo (kata sandi semuanya: password)
        $prov = [];
        foreach ([['SMILEY Restaurant', 'smiley'], ['Italy Restaurant', 'italy'], ['Sun Hotel', 'sunhotel'], ['Bakery Delight', 'bakery'], ['Katering Berkah', 'katering']] as [$n, $e]) {
            $prov[$n] = User::create(['name' => $n, 'organization' => $n, 'email' => "$e@foodloop.test", 'password' => 'password', 'role' => 'provider', 'phone' => '081200000' . count($prov)]);
        }
        $rec = [
            User::create(['name' => 'Budiman Pekerti', 'organization' => 'Relawan FoodLoop', 'email' => 'relawan@foodloop.test', 'password' => 'password', 'role' => 'recipient', 'phone' => '081311111111']),
            User::create(['name' => 'Panti Asuhan Kasih', 'organization' => 'Panti Asuhan Kasih', 'email' => 'panti@foodloop.test', 'password' => 'password', 'role' => 'recipient', 'phone' => '081322222222']),
        ];

        foreach ([
            ['Panti Asuhan Kasih', 'layak', 'Jakarta Selatan', -6.2600, 106.8100], ['Panti Jompo Sejahtera', 'layak', 'Jakarta Timur', -6.2300, 106.9000],
            ['Dapur Umum Warga Tangerang', 'layak', 'Tangerang', -6.1800, 106.6300], ['BSF Farm Tangerang', 'organik', 'Tangerang', -6.2000, 106.6500],
            ['Komposter Bekasi', 'organik', 'Bekasi', -6.2500, 106.9900], ['Rumah Kompos Depok', 'organik', 'Depok', -6.3900, 106.8200],
        ] as [$n, $t, $a, $la, $ln]) Partner::create(['name' => $n, 'type' => $t, 'address' => $a, 'lat' => $la, 'lng' => $ln]);

        foreach ([ // judul, penyedia, kategori, porsi, kg, jam sisa, lat, lng, status
            ['Roti berlebih toko', 'Bakery Delight', 'layak', 8, 3.2, 0.7, -6.2000, 106.8300, 'waiting'],
            ['Nasi kotak acara kampus', 'SMILEY Restaurant', 'layak', 40, 16, 4, -6.2300, 106.7600, 'waiting'],
            ['Katering rapat 30 porsi', 'Katering Berkah', 'layak', 30, 12, 3, -6.1700, 106.8600, 'claimed'],
            ['Sisa sayur & buah', 'Sun Hotel', 'organik', 0, 15, 8, -6.2900, 106.9000, 'waiting'],
            ['Sisa olahan dapur', 'Italy Restaurant', 'organik', 0, 9, 6, -6.1500, 106.7000, 'waiting'],
            ['Pasta & salad prasmanan', 'Italy Restaurant', 'layak', 25, 10, 2.5, -6.2200, 106.8000, 'on_the_way'],
        ] as [$t, $p, $c, $por, $kg, $h, $la, $ln, $s]) {
            $taken = $s !== 'waiting';
            Report::create(['title' => $t, 'provider_name' => $p, 'provider_id' => $prov[$p]->id, 'category' => $c, 'portions' => $por, 'weight_kg' => $kg,
                'expires_at' => now()->addMinutes((int) ($h * 60)), 'lat' => $la, 'lng' => $ln, 'status' => $s,
                'partner_id' => Partner::nearest($c, $la, $ln)->id, 'urgent' => $c === 'layak' && $h < 1,
                'recipient_id' => $taken ? $rec[0]->id : null, 'volunteer_name' => $taken ? $rec[0]->name : null]);
        }
        foreach ([
            ['SMILEY Restaurant', 'layak', 120, 48, false], ['SMILEY Restaurant', 'layak', 200, 80, false], ['Italy Restaurant', 'layak', 150, 60, false],
            ['Sun Hotel', 'organik', 0, 55, false], ['Sun Hotel', 'organik', 0, 40, false], ['Bakery Delight', 'layak', 60, 24, true],
            ['Katering Berkah', 'layak', 90, 36, false], ['Italy Restaurant', 'organik', 0, 30, false], ['Bakery Delight', 'layak', 45, 18, true],
        ] as $i => [$p, $c, $por, $kg, $u]) {
            Report::create(['title' => 'Penyaluran ' . ($i + 1), 'provider_name' => $p, 'provider_id' => $prov[$p]->id, 'category' => $c, 'portions' => $por, 'weight_kg' => $kg, 'urgent' => $u,
                'expires_at' => now()->subDays(($i + 1) * 25), 'delivered_at' => now()->subDays(($i + 1) * 25)->addHours(2), 'lat' => -6.22, 'lng' => 106.82,
                'status' => 'delivered', 'partner_id' => Partner::nearest($c, -6.22, 106.82)->id,
                'recipient_id' => $rec[$i % 2]->id, 'volunteer_name' => $rec[$i % 2]->name]);
        }
    }
}
