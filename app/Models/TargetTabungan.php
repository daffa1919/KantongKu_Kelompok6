<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TargetTabungan extends Model
{
    protected $fillable = [
        'user_id',
        'nama_target',
        'target_nominal',
        'terkumpul',
        'tanggal_target',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'target_nominal' => 'decimal:2',
            'terkumpul' => 'decimal:2',
            'tanggal_target' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    public function getPersentaseAttribute(): float
    {
        if ((float) $this->target_nominal <= 0) {
            return 0;
        }

        return min(
            100,
            round(
                ((float) $this->terkumpul / (float) $this->target_nominal) * 100,
                1
            )
        );
    }
}
