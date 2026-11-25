<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\Report;
use Illuminate\Support\Collection;

/** Satu-satunya sumber perhitungan dampak & kelayakan di seluruh aplikasi. */
class ImpactCalculator
{
    public const KG_PER_PORTION = 0.4;  // asumsi 1 porsi = 0,4 kg

    public const CO2E_PER_KG = 0.69;    // estimasi kg CO2e dicegah per kg (ganti dengan sumber resmi)

    public static function portionsFromKg(float $kg): int
    {
        return max(1, (int) round($kg / self::KG_PER_PORTION));
    }

    public static function kgFromPortions(int $p): float
    {
        return round($p * self::KG_PER_PORTION, 1);
    }

    public static function co2e(float $kg): float
    {
        return round($kg * self::CO2E_PER_KG, 1);
    }

    /** q1 suhu aman (y), q2 bau asam (n), q3 >4 jam suhu ruang (n), q4 kemasan baik (y). */
    public static function feasible(array $a): bool
    {
        return ($a['q1'] ?? '') === 'y' && ($a['q2'] ?? '') === 'n' && ($a['q3'] ?? '') === 'n' && ($a['q4'] ?? '') === 'y';
    }

    public static function totals(): array
    {
        $kg = (float) Report::where('status', 'delivered')->sum('weight_kg');

        return [
            'kg' => $kg,
            'meals' => (int) Report::where('status', 'delivered')->where('category', 'layak')->sum('portions'),
            'co2_ton' => round(self::co2e($kg) / 1000, 2),
            'reports' => Report::count(),
            'partners' => Partner::count(),
        ];
    }

    public static function categories(): array
    {
        $d = fn () => Report::where('status', 'delivered');

        return [
            ['Layak Konsumsi', (float) $d()->where('category', 'layak')->where('urgent', false)->sum('weight_kg'), '#1f6b3a'],
            ['Organik', (float) $d()->where('category', 'organik')->sum('weight_kg'), '#f28c28'],
            ['Segera Ambil', (float) $d()->where('urgent', true)->sum('weight_kg'), '#d9363e'],
        ];
    }

    public static function monthly(int $n = 8): array
    {
        $out = [];
        for ($i = $n - 1; $i >= 0; $i--) {
            $m = now()->startOfMonth()->subMonths($i);
            $out[$m->format('Y-m')] = ['m' => $m->locale('id')->translatedFormat('M'), 'layak' => 0.0, 'organik' => 0.0, 'urgent' => 0.0];
        }
        foreach (Report::where('status', 'delivered')->where('delivered_at', '>=', now()->startOfMonth()->subMonths($n - 1))->get() as $r) {
            $k = $r->delivered_at->format('Y-m');
            if (isset($out[$k])) {
                $out[$k][$r->urgent ? 'urgent' : $r->category] += $r->weight_kg;
            }
        }

        return array_values($out);
    }

    public const CO2E_PER_CAR_KM = 0.12;    // estimasi kg CO2e per km mobil penumpang

    public const CO2E_PER_TREE_YEAR = 22.0; // estimasi kg CO2 diserap satu pohon per tahun

    /** Tingkatan kontributor berdasarkan total kg yang tersalurkan. */
    public const LEVELS = [0 => 'Pemula Peduli', 20 => 'Penyelamat Pangan', 100 => 'Pahlawan Pangan', 300 => 'Legenda Loop'];

    /**
     * Padanan dampak agar angka CO2e mudah dibayangkan.
     *
     * @return array{co2e: float, car_km: int, trees: float, meals: int}
     */
    public static function equivalents(float $kg): array
    {
        $co2e = self::co2e($kg);

        return [
            'co2e' => $co2e,
            'car_km' => (int) round($co2e / self::CO2E_PER_CAR_KM),
            'trees' => round($co2e / self::CO2E_PER_TREE_YEAR, 1),
            'meals' => $kg > 0 ? self::portionsFromKg($kg) : 0,
        ];
    }

    /**
     * Level & lencana pencapaian dari kumpulan laporan yang sudah tersalurkan.
     *
     * @param  Collection<int, Report>  $delivered
     * @return array{kg: float, level: string, next: ?string, toNext: float, progress: int, badges: list<array{icon: string, name: string, desc: string, unlocked: bool}>}
     */
    public static function journey($delivered): array
    {
        $kg = (float) $delivered->sum('weight_kg');
        $thresholds = array_keys(self::LEVELS);
        $current = 0;
        foreach ($thresholds as $threshold) {
            if ($kg >= $threshold) {
                $current = $threshold;
            }
        }
        $nextThreshold = collect($thresholds)->first(fn ($threshold) => $threshold > $kg);
        $progress = $nextThreshold === null ? 100 : (int) round(($kg - $current) / ($nextThreshold - $current) * 100);

        $count = $delivered->count();
        $badges = [
            ['🌱', 'Langkah Pertama', 'Selesaikan 1 penyaluran', $count >= 1],
            ['🍱', 'Seratus Porsi', 'Salurkan 100 porsi layak konsumsi', $delivered->where('category', 'layak')->sum('portions') >= 100],
            ['♻️', 'Sahabat Bumi', 'Olah sisa organik menjadi kompos/BSF', $delivered->where('category', 'organik')->isNotEmpty()],
            ['⚡', 'Penyelamat Kilat', 'Selamatkan makanan berstatus darurat', $delivered->where('urgent', true)->isNotEmpty()],
            ['🔥', 'Konsisten', 'Selesaikan 5 penyaluran', $count >= 5],
            ['🌍', 'Penjaga Iklim', 'Cegah 100 kg CO₂e', self::co2e($kg) >= 100],
        ];

        return [
            'kg' => $kg,
            'level' => self::LEVELS[$current],
            'next' => $nextThreshold === null ? null : self::LEVELS[$nextThreshold],
            'toNext' => $nextThreshold === null ? 0.0 : round($nextThreshold - $kg, 1),
            'progress' => max(0, min(100, $progress)),
            'badges' => array_map(fn ($b) => ['icon' => $b[0], 'name' => $b[1], 'desc' => $b[2], 'unlocked' => $b[3]], $badges),
        ];
    }

    public static function leaderboard(int $n = 5)
    {
        return Report::where('status', 'delivered')->selectRaw('provider_name, SUM(weight_kg) as kg, COUNT(*) as trips')
            ->groupBy('provider_name')->orderByDesc('kg')->limit($n)->get()
            ->map(fn ($r) => ['name' => $r->provider_name, 'kg' => (float) $r->kg, 'co2' => self::co2e((float) $r->kg), 'trips' => (int) $r->trips]);
    }
}
