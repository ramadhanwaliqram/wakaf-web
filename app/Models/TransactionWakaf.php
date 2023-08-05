<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionWakaf extends Model
{
    use HasFactory, HasUuids;

    protected $table = "transaction_wakafs";
    protected $keyType = "string";
    protected $primaryKey = "uuid";
    public $incrementing = false;

    protected $fillable = [
        'wakaf_uuid',
        'user_uuid',
        'signature',
        'amount',
        'reference',
        'status',
    ];

    public function references(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reference', 'uuid');
    }

    public function signatures(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function wakaf(): BelongsTo
    {
        return $this->belongsTo(Wakaf::class, 'wakaf_uuid', 'uuid');
    }
}
