<?php

namespace EgeaTech\LaravelResponses\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use EgeaTech\LaravelExceptions\Interfaces\Exceptions\LogicErrorException;

class PaginatedApiResponse extends JsonResponse
{
    public function __construct(LengthAwarePaginator $paginatorData, string $responseFormatter, $httpStatus = self::HTTP_OK, ?LogicErrorException $logicException = null)
    {
        if (!class_exists($responseFormatter)) {
            throw new \RuntimeException("Class [{$responseFormatter}] does not exist");
        }

        if (!((new $responseFormatter(null)) instanceof JsonResource)) {
            throw new \RuntimeException("Class [{$responseFormatter}] should be an instance of " . JsonResource::class);
        }

        $responseObject = [
            'success' => $httpStatus < self::HTTP_BAD_REQUEST,
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
            'data' => $responseFormatter::collection($paginatorData->items()),
        ];

        if ($logicException) {
            $httpStatus = $logicException->getCode();
            $responseObject['errors'] = [
                'general_error' => [$logicException->getMessageKey()]
            ];
        } else {
            $responseObject['errors'] = [];
        }

        parent::__construct($responseObject, $httpStatus);
    }
}
