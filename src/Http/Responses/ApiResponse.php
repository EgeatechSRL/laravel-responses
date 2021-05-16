<?php

namespace EgeaTech\LaravelResponses\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use EgeaTech\LaravelExceptions\Interfaces\Exceptions\LogicErrorException;

class ApiResponse extends JsonResponse
{
    public function __construct(?Model $modelInstance, Request $incomingRequest, JsonResource $jsonResource, int $status = self::HTTP_OK, ?LogicErrorException $logicException = null)
    {
        $resourceClass = get_class($jsonResource);
        $responseObject = [
            'success' => $status < self::HTTP_BAD_REQUEST,
            'data' => $modelInstance
                ? (new $resourceClass($modelInstance))->toArray($incomingRequest)
                : null,
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
