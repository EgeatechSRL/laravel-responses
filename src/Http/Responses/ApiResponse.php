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
     * @param null|Model|Collection $responseData
     * @param string $responseFormatter A Illuminate\Http\Resources\Json\JsonResource class instance
     * @param int $httpHttpStatus
     * @param null|LogicErrorException $logicException
     */
    public function __construct($responseData, string $responseFormatter, int $httpHttpStatus = self::HTTP_OK, ?LogicErrorException $logicException = null)
    {
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
        } else {
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
