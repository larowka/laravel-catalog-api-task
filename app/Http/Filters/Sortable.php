<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;

trait Sortable
{
    protected Builder $builder;

    protected static array $sortDirections = [
        'asc',
        'desc'
    ];

    abstract protected function &sortColumns() : array;

    protected function sort(string $column, string $direction)
    {
        $errors = [];
        if (!in_array($column, $this->sortColumns()))
            $errors = array_merge($errors, [$column => 'Invalid sort column']);

        if (!in_array($direction, self::$sortDirections))
            $errors = array_merge($errors, [$direction => 'Invalid sort direction']);

        if (count($errors) == 0)
            return  $this->builder->orderBy($column, $direction);

        // Validation and response here because through applying filters allowed sort columns can be augmented
        throw new HttpResponseException(
            response()->json(
                [
                    'error' => [
                        'sort' => [
                            $errors
                        ]
                    ]
                ], 422
            )
        );
    }

    protected function addSortColumn(string $key)
    {
        array_push($this->sortColumns(), $key);
    }
}
