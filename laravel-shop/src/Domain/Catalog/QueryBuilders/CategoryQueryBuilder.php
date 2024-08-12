<?php

namespace Domain\Catalog\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

final class CategoryQueryBuilder extends Builder
{
    public function homePage(): CategoryQueryBuilder
    {
        return $this->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(6)
        ;
    }
}
