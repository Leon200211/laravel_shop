<?php

namespace Domain\Product\Collections;

use Illuminate\Database\Eloquent\Collection;

final class OptionValueCollection extends Collection
{
    public function keyValues()
    {
        return $this->mapToGroups(function ($item) {
            return [$item->option->title => $item];
        });
    }
}
