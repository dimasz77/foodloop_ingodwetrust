<?php
namespace App\Services;

use App\Models\{Partner,Report};

/** Satu-satunya sumber perhitungan dampak & kelayakan di seluruh aplikasi. */
class ImpactCalculator
{
    public const KG_PER_PORTION = 0.4;  // asumsi 1 porsi = 0,4 kg
    public const CO2E_PER_KG = 0.69;    // estimasi kg CO2e dicegah per kg (ganti dengan sumber resmi)

    public static function portionsFromKg(float $kg): int { return max(1, (int) round($kg / self::KG_PER_PORTION)); }
    public static function kgFromPortions(int $p): float { return round($p * self::KG_PER_PORTION, 1); }
    public static function co2e(float $kg): float { return round($kg * self::CO2E_PER_KG, 1); }

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
            ['Layak Konsumsi', (float) $d()->where('category', 'layak')->where('urgent', false)->sum('weight_kg'), '#047857'],
            ['Organik', (float) $d()->where('category', 'organik')->sum('weight_kg'), '#f59e0b'],
            ['Segera Ambil', (float) $d()->where('urgent', true)->sum('weight_kg'), '#dc2626'],
        ];
    }

    public static function leaderboard(int $n = 5)
    {
        return Report::where('status', 'delivered')->selectRaw('provider_name, SUM(weight_kg) as kg')
            ->groupBy('provider_name')->orderByDesc('kg')->limit($n)->get()
            ->map(fn ($r) => ['name' => $r->provider_name, 'kg' => (float) $r->kg, 'co2' => self::co2e((float) $r->kg)]);
    }
}
