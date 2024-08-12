<?php

namespace Support\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Support\ValueObjects\Price;

class PriceCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model  $model
     * @param string  $key
     * @param mixed  $value
     * @param array  $attributes
     *
     * @return Price
     */
    public function get($model, string $key, $value, array $attributes): Price
    {
        return Price::make($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model  $model
     * @param string  $key
     * @param mixed  $value
     * @param array  $attributes
     *
     * @return int
     */
    public function set($model, string $key, $value, array $attributes): int
    {
        if (!$value instanceof Price) {
            $value = Price::make($value);
        }

        return $value->raw();
    }
}
