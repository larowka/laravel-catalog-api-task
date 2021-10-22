<?php

namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Filters\BuildingFilter;
use App\Http\Requests\BuildingRequest;
use App\Http\Resources\BuildingResource;
use App\Models\Building;

class BuildingController extends Controller
{
    private BuildingFilter $filter;

    public function __construct(BuildingRequest $request)
    {
        $this->filter = new BuildingFilter($request);
    }

    public function index(): JsonResource
    {
        $buildings = Building::filter($this->filter)->paginate(10);
        return BuildingResource::collection($buildings);
    }

    public function show(int $id): JsonResource
    {
        $buildings = Building::filter($this->filter)->findOrFail($id);
        return new BuildingResource($buildings);
    }
}
