<?php

namespace App\Http\Filters;

use App\Models\Company;

class CompanyFilter extends QueryFilter
{
    use Sortable;
    use Searchable;

    private array $sortColumns = [
        'id',
        'title',
        'created_at'
    ];

    protected function &sortColumns(): array
    {
        return $this->sortColumns;
    }

    public function buildings(string $buildings): void
    {
        $buildings = explode(',', $buildings);
        $this->builder->whereIn('building_id', $buildings);
    }

    public function rubrics(string $rubrics): void
    {
        $rubrics = explode(',', $rubrics);
        $this->builder->whereHas('rubrics', function ($builder) use ($rubrics) {
            $builder->whereIn('rubrics.id', $rubrics);
        });
        //$this->with('rubrics');
    }

    public function with(string $with): void
    {
        $relations = explode(',', $with);
        foreach ($relations as $relation) {
            if (in_array($relation, Company::$relationships))
                $this->builder->with($relation);
        }
    }

    private function addCoordinates(): void
    {
        $this->builder
            ->leftJoin('buildings', 'companies.building_id', '=', 'buildings.id')
            ->addSelect('companies.*', 'buildings.latitude', 'buildings.longitude');
    }

    protected function afterApply(): void
    {
        if ($this->searchLogic()) {
            $this->addCoordinates();
            $this->search();
            $this->addSortColumn('distance');
        }
    }
}
