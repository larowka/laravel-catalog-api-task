<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    protected Request $request;
    protected Builder $builder;
    protected Collection $filters;

    public function __construct(Request $request = null)
    {
        $this->filters = new Collection();

        if ($request) {
            $this->setFilters($request->validated());
        }
    }

    public function apply(Builder $builder) : void
    {
        $this->beforeApply();
        $this->builder = $builder;
        foreach ($this->filters as $field => $value) {
            $method = Str::camel($field);
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], (array)$value);
            } elseif (property_exists($this, $method)) {
                call_user_func_array([$this, '__set'], [$field, $value]);
            }
        }
        $this->afterApply();
    }

    public function setFilters(array $filters = []): self
    {
        $this->filters = collect($filters);
        return $this;
    }

    protected function beforeApply() : void {}
    protected function afterApply() : void {}
}
