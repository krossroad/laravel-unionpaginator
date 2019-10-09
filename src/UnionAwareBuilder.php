<?php

namespace Krossroad\UnionPaginator;

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

        $perPage = $perPage ?: $this->model->getPerPage();

        $results = ($total = $this->getCountForUnionPagination($this->toBase()))
            ? $this->forPage($page, $perPage)->get($columns)
            : $this->model->newCollection();

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
     * @param  QueryBuilder $query
     * @return int
     */
    protected function getCountForUnionPagination($query)
    {
        $conn = $this->getConnection();
        $qb   = new QueryBuilder($conn, $conn->getQueryGrammar(), $conn->getPostProcessor());

        $tableSql = sprintf('(%s) as table_count', $query->toSql());
        $tableSql = $conn->raw($tableSql);

        $result = $qb->select([$conn->raw('count(1) as row_count')])
            ->from($tableSql)
            ->mergeBindings($query)
            ->first();

        return $result->row_count;
    }
}
