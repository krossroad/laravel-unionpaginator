<?php

namespace Krossroad\UnionPaginator;

trait UnionPaginatorTrait
{
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return UnionBuilder|static
     */
    public function newEloquentBuilder($query)
    {
        return new UnionAwareBuilder($query);
    }

}
