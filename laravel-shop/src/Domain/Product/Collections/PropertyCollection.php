<?php

namespace Domain\Product\Collections;

use Illuminate\Database\Eloquent\Collection;

final class PropertyCollection extends Collection
{
    public function keyValues()
    {
        return $this->mapWithKeys(fn($property) => [$property->title => $property->pivot->value]);
    }
}
