<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = 'products';
    protected $fillable = ['name', 'price', 'description', 'is_available', 'category_id'];
    protected $casts = [
        'is_available' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return$this->belongsTo(Category::class);
    }
}
