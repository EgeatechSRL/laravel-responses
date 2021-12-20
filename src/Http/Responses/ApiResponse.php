<?php

namespace EgeaTech\LaravelResponses\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use EgeaTech\LaravelExceptions\Interfaces\Exceptions\LogicErrorException;

class ApiResponse extends JsonResponse
{
    /**
     * Creates a new ApiResponse class instance.
     *
     * @param null|Model|Collection|mixed $responseData
     * @param null|string $responseFormatter An Illuminate\Http\Resources\Json\JsonResource class instance
     * @param int $httpHttpStatus
     * @param null|LogicErrorException $logicException
     */
    public function __construct($responseData, ?string $responseFormatter = null, int $httpHttpStatus = self::HTTP_OK, ?LogicErrorException $logicException = null)
    {
        if (!is_null($responseFormatter)) {
            if (!class_exists($responseFormatter)) {
                throw new \RuntimeException("Class [{$responseFormatter}] does not exist");
            }

            if (!((new $responseFormatter(null)) instanceof JsonResource)) {
                throw new \RuntimeException("Class [{$responseFormatter}] should be an instance of " . JsonResource::class);
            }

            if ($responseData instanceof Model) {
                $data = new $responseFormatter($responseData);
            } elseif ($responseData instanceof Collection) {
                $data = $responseFormatter::collection($responseData);
            } elseif (!is_null($responseData)) {
                $data = new $responseFormatter($responseData);
            } else {
                $data = $responseData;
            }
        } else {
            if ($responseData instanceof Model || $responseData instanceof Collection) {
                throw new \RuntimeException("When providing a Model or a Collection to an ApiResponse object, responseFormatter must be a valid instance of " . JsonResource::class);
            }

            $data = $responseData;
        }

        $responseObject = [
            'success' => $httpHttpStatus < self::HTTP_BAD_REQUEST,
            'data' => $data,
        ];

        if ($logicException) {
            $httpHttpStatus = $logicException->getCode();
            $responseObject['errors'] = [
                'general_error' => [$logicException->getMessageKey()]
            ];
        } else {
            $responseObject['errors'] = [];
        }

        parent::__construct($responseObject, $httpHttpStatus);
    }
}
