<?php

namespace App\Models;

use App\Casts\SeoUrlCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Worksome\RequestFactories\Concerns\HasFactory;

class Seo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
    ];

    protected $casts = [
        'url' => SeoUrlCast::class,
    ];

    protected static function boot()
    {
        parent::boot();

        $forgetSeoCache = function (Seo $model) {
            Cache::forget('seo_' . str($model->url)->slug('_'));
        };

        static::created($forgetSeoCache);
        static::updated($forgetSeoCache);
        static::deleted($forgetSeoCache);
    }
}
