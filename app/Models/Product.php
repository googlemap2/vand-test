<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'code',
        'name',
        'deleted',
        'created_at',
        'updated_at'
    ];

    public function productVariations(): HasMany
    {
        return $this->hasMany(ProductVariations::class);
    }
}
