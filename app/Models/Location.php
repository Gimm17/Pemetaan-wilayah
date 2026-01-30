<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    // use SoftDeletes; // Disabled by user request

    protected $fillable = [
        'kode_desa', 'shape', 'nama', 'nop', 'luas', 'sertpikat', 'njop', 'luas_bangu', 'user_perum',
        'latitude', 'longitude', 'category_id',
        'created_by', 'updated_by', 'approved_by', 'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'luas' => 'decimal:2',
        'njop' => 'decimal:2',
        'luas_bangu' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(LocationPhoto::class);
    }
}
