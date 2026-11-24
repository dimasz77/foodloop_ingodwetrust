<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $guarded = [];
    protected $casts = ['lat' => 'float', 'lng' => 'float'];

    public static function km(float $a, float $b, float $c, float $d): float
    {
        $q = M_PI / 180;
        $x = sin(($c - $a) * $q / 2) ** 2 + cos($a * $q) * cos($c * $q) * sin(($d - $b) * $q / 2) ** 2;
        return 12742 * asin(sqrt($x));
    }

    public static function nearest(string $type, float $lat, float $lng): ?self
    {
        return static::where('type', $type)->get()->sortBy(fn ($p) => self::km($lat, $lng, $p->lat, $p->lng))->first();
    }
}
