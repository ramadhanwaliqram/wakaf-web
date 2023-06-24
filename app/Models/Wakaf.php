<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wakaf extends Model
{
    use HasFactory, HasUuids;

    protected $table = "wakafs";
    protected $keyType = "string";
    protected $primaryKey = "uuid";
    public $incrementing = false;

    protected $fillable = [
        'title',
        'description',
        'target',
        'thumbnail',
    ];

    public function transaction(): HasMany
    {
        return $this->hasMany(TransactionWakaf::class, 'wakaf_uuid', 'uuid');
    }
}
