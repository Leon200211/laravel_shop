<?php

namespace App\Models;

use App\Traits\Models\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'slug',
        'title',
        'thumbnail',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
