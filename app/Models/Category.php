<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;

    protected $table = 'categories';
    protected $fillable = [
        'name',
        'parent_id',
        '_lft',
        '_rgt'
    ];

    public function getIndentedNameAttribute(): string
    {
        return str_repeat('—', $this->depth) . ' ' . $this->name;
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
