<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonitorType extends Model
{
    const PING = 'ping';

    protected $fillable = [
        'name',
    ];

    public static function getTypes(): array
    {
        return [
            self::PING,
        ];
    }

    public function monitors(): HasMany
    {
        return $this->hasMany(Monitor::class);
    }
}
