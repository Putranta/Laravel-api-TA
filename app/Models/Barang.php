<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barang extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'img', 'desc', 'status', 'category_id'];

    public function Category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    protected function img(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => asset('/storage/barang/' . $image),
        );
    }
}
