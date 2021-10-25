<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResourceCollection extends ResourceCollection
{
    protected array $meta;

    public function __construct(LengthAwarePaginator $resource)
    {
        $this->meta = [
            'total' => $resource->total(),
            'perPage' => $resource->perPage(),
            'currentPage' => $resource->currentPage(),
            'totalPages' => $resource->lastPage(),
            'nextPage'  => $resource->nextPageUrl(),
            'prevPage'  => $resource->previousPageUrl(),
        ];

        parent::__construct($resource->getCollection());
    }

    public function toArray($request): array
    {
        return [
            'meta' => $this->meta,
            'data' => $this->collection
        ];
    }
}
