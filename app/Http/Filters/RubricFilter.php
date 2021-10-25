<?php

namespace App\Http\Filters;

use App\Models\Rubric;

class RubricFilter extends QueryFilter
{
    use Sortable;

    protected function &sortColumns(): array
    {
        return Rubric::$sortColumns;
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
