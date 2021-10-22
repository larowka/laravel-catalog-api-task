<?php

namespace App\Http\Filters;

use App\Models\Rubric;

class RubricFilter extends QueryFilter
{
    use Sortable;

    private array $sortColumns = [
        'id',
        'title',
        'created_at'
    ];

    protected function &sortColumns(): array
    {
        return $this->sortColumns;
    }

    public function with(string $with): void
    {
        $relations = array_filter(explode(',', $with));
        foreach ($relations as $relation) {
            if (in_array($relation, Rubric::$relationships))
                $this->builder->with($relation);
        }
    }
}
