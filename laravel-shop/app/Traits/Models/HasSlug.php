<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        // TODO Удалить time из слага 2 блок 4 тема
        static::creating(function (Model $item) {
            $item->slug = $item->slug
                ?? str($item->{self::slugFrom()})
                    ->append(time() . uniqid())
                    ->slug();
        });
    }

    public static function slugFrom(): string
    {
        return 'title';
    }
}
