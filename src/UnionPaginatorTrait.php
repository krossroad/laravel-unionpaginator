<?php

namespace Krossroad\UnionPaginator;

trait UnionPaginatorTrait
{
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Krossroad\UnionPaginator\UnionAwareBuilder|static
     */
    public function newEloquentBuilder($query)
    {
        return new UnionAwareBuilder($query);
    }
}
