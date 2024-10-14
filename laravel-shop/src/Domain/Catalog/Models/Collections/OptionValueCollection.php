<?php

namespace Domain\Catalog\Models\Collections;

use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Collection;

class OptionValueCollection extends DatabaseCollection
{
    public function keyValues(): OptionValueCollection|Collection
    {
        return $this->mapToGroups(function ($item) {
            return [$item->option->title => $item];
        });
    }
}
