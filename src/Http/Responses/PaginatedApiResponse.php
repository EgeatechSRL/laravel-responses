<?php

namespace EgeaTech\LaravelResponses\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use EgeaTech\LaravelExceptions\Interfaces\Exceptions\LogicErrorException;

class PaginatedApiResponse extends JsonResponse
{
    public function __construct(LengthAwarePaginator $paginatorData, Request $incomingRequest, JsonResource $jsonResource, $status = self::HTTP_OK, ?LogicErrorException $logicException = null)
    {
        $resourceClass = get_class($jsonResource);
        $responseObject = [
            'success' => $status < self::HTTP_BAD_REQUEST,
            'pagination' => [
                'total' => $paginatorData->total(),

                'per_page' => $paginatorData->perPage(),
                'current_page' => $paginatorData->currentPage(),
                'last_page' => $paginatorData->lastPage(),

                'first_page_url' => $paginatorData->url(1),
                'last_page_url' => $paginatorData->url($paginatorData->lastPage()),
                'next_page_url' => $paginatorData->nextPageUrl(),
                'previous_page_url' => $paginatorData->previousPageUrl(),
            ],
            'data' => array_map(
                fn($paginationItem): array => (new $resourceClass($paginationItem))->toArray($incomingRequest),
                $paginatorData->items()
            ),
        ];

        if ($logicException) {
            $status = $logicException->getCode();
            $responseObject['errors'] = [
                'general_error' => [$logicException->getMessageKey()]
            ];
        } else {
            $responseObject['errors'] = [];
        }

        parent::__construct($responseObject, $status);
    }
}
