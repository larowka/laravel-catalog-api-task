<?php

namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Filters\RubricFilter;
use App\Http\Requests\RubricRequest;
use App\Http\Resources\RubricResource;
use App\Models\Rubric;

class RubricController extends Controller
{
    private RubricFilter $filter;

    public function __construct(RubricRequest $request)
    {
        $this->filter = new RubricFilter($request);
    }

    public function index(): JsonResource
    {
        $rubrics = Rubric::filter($this->filter)->paginate(10);
        return RubricResource::collection($rubrics);
    }

    public function show(int $id): JsonResource
    {
        $rubrics = Rubric::filter($this->filter)->findOrFail($id);
        return new RubricResource($rubrics);
    }
}
