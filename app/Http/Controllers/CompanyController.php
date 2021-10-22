<?php

namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Filters\CompanyFilter;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;

class CompanyController extends Controller
{
    private CompanyFilter $filter;

    public function __construct(CompanyRequest $request)
    {
        $this->filter = new CompanyFilter($request);
    }

    public function index(): JsonResource
    {
        $companies = Company::filter($this->filter)->paginate(10);
        return CompanyResource::collection($companies);
    }

    public function show(int $id): JsonResource
    {
        $companies = Company::filter($this->filter)->findOrFail($id);
        return new CompanyResource($companies);
    }
}
