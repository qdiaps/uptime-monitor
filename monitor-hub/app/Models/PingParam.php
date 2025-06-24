<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PingParam extends Model
{
    protected $fillable = [
        'interval',
        'host',
        'count_packet',
    ];

    public function monitor(): HasOne
    {
        return $this->hasOne(Monitor::class);
    }
}
