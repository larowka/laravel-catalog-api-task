<?php

namespace App\Http\Filters;

use App\Models\Building;

class BuildingFilter extends QueryFilter
{
    use Sortable;
    use Searchable;

    protected function &sortColumns(): array
    {
        return Building::$sortColumns;
    }

    public function companies(string $companies): void
    {
        $companies = explode(',', $companies);
        $this->builder->whereHas('companies', function ($builder) use ($companies) {
            $builder->whereIn('companies.id', $companies);
        });
        $this->with('companies');
    }

    public function with(string $with): void
    {
        $relations = explode(',', $with);
        foreach ($relations as $relation) {
            if (in_array($relation, Building::$relationships))
                $this->builder->with($relation);
        }
    }

    protected function afterApply() : void
    {
        if ($this->searchLogic()) {
            $this->builder->select();
            $this->search();
            $this->addSortColumn('distance');
        }
    }
}
