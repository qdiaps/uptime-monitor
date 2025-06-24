<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Monitor extends Model
{
    protected $fillable = [
        'user_id'
    ];

    public function monitorType(): BelongsTo
    {
        return $this->belongsTo(MonitorType::class);
    }

    public function pingParam(): BelongsTo
    {
        return $this->belongsTo(PingParam::class);
    }
}
