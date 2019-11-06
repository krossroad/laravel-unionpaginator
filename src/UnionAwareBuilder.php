<?php

namespace Krossroad\UnionPaginator;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\Paginator;

class UnionAwareBuilder extends Builder
{
    /**
     * Paginate the query with union.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function unionPaginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: $this->getCurrentModel()->getPerPage();

        $results = ($total = $this->getCountForUnionPagination($this->toBase()))
            ? $this->forPage($page, $perPage)->get($columns)
            : $this->getCurrentModel()->newCollection();

        return $this->paginator(
            $results,
            $total,
            $perPage,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
    }

    /**
     * Returns the custom query builder
     *
     * @param $connection Connection|\Illuminate\Database\ConnectionInterface
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getCustomQueryBuilder($connection)
    {
        return new QueryBuilder(
            $connection,
            $connection->getQueryGrammar(),
            $connection->getPostProcessor()
        );
    }

    /**
     * @param  QueryBuilder $query
     * @return int
     */
    protected function getCountForUnionPagination($query)
    {
        $conn = $this->getConnection();
        $queryBuilder = $this->getCustomQueryBuilder($conn);

        $tableSql = sprintf('(%s) as table_count', $query->toSql());
        $tableSql = $conn->raw($tableSql);

        $result = $queryBuilder
            ->select([$conn->raw('count(1) as row_count')])
            ->from($tableSql)
            ->mergeBindings($query)
            ->first();

        return $result->row_count;
    }

    /**
     * Returns the current Model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getCurrentModel()
    {
        return $this->model;
    }
}
