<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    protected Builder $builder;

    protected string $defaultSortColumn = 'id';
    protected string $defaultSortDirection = 'asd';

    abstract protected function &sortColumns() : array;

    protected function sortDirections(): array
    {
        return [
            'asc',
            'desc'
        ];
    }

    protected function sort(string $column, string $direction)
    {
        if (!in_array($column, $this->sortColumns())) $column = $this->defaultSortColumn;
        if (!in_array($direction, $this->sortDirections())) $direction = $this->defaultSortDirection;

        $this->builder->orderBy($column, $direction);
    }

    protected function addSortColumn(string $key)
    {
        array_push($this->sortColumns(), $key);
    }
}
