# Laravel Responses

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

A package to provide some standardized response classes.

## Installation

This package supports Laravel 7 and 8 but requires **at least** PHP 7.4. 

Via Composer

``` bash
$ composer require egeatech/laravel-responses
```

## Usage

Simply return a new instance of either `ApiResponse` or `PaginatedApiResponse` from your controller method.

Both classes share a similar signature for their constructors:

Here is the constructor for `ApiResponse`:

```php
public function __construct(
    # Either null or a valid Eloquent model instance
    ?\Illuminate\Database\Eloquent\Model $modelInstance,
    
    # The incoming request instance
    \Symfony\Component\HttpFoundation\Request $incomingRequest,
    
    # An empty JSON api resource instance, to be used to know how to map response data
    \Illuminate\Http\Resources\Json\JsonResource $jsonResource,
    
    # A valid HTTP status code, to be returned to the caller
    int $status = \Illuminate\Http\JsonResponse::HTTP_OK,
    
    # An optional ApplicationException instance, to properly provide a valid error message representation
    ?\EgeaTech\LaravelExceptions\Interfaces\Exceptions\LogicErrorException $logicException = null 
) {}
```

And here the one for `PaginatedApiResponse` class:

```php
public function __construct(
    # A standard Laravel paginator instance, holding both data and pagination information
    \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginatorData,
    
    # The incoming request instance
    \Symfony\Component\HttpFoundation\Request $incomingRequest,
    
    # An empty JSON api resource instance, to be used to know how to map response data
    \Illuminate\Http\Resources\Json\JsonResource $jsonResource,
    
    # A valid HTTP status code, to be returned to the caller
    int $status = \Illuminate\Http\JsonResponse::HTTP_OK,
    
    # An optional ApplicationException instance, to properly provide a valid error message representation
    ?\EgeaTech\LaravelExceptions\Interfaces\Exceptions\LogicErrorException $logicException = null 
) {}
```

An example usage for the `ApiResponse` class could be the following:

```php
<?php

namespace App\Http\Controllers\Api\TestController;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModelUpdateRequest;
use EgeaTech\LaravelResponses\Http\Responses\ApiResponse;
use EgeaTech\LaravelExceptions\Interfaces\Exceptions\LogicErrorException;

class TestController extends Controller
{
    public function __invoke(ModelUpdateRequest $request): ApiResponse
    {
        $occurredException = null;
        $databaseModel = null;

        try {
            $modelData = $request->validated();
            $databaseModel = $this->updateModel($modelData);
        } catch (LogicErrorException $exception) {
            $occurredException = $exception;
        }

        return new ApiResponse(
            $databaseModel,
            $request,
            new DatabaseModelResource(null),
            $occurredException
                ? $occurredException->getCode()
                : ApiResponse::HTTP_ACCEPTED,
            $occurredException
        );
    }
}
```

For the other class, usage is the same as long as you provide a `LenghtAwarePaginator` instance as first parameter.

## Change log

Please see the [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [Egea Tecnologie Informatiche][link-author]
- [Marco Guidolin](mailto:m.guidolin@egeatech.com)

## License

The software is licensed under MIT. Please see the [LICENSE](LICENSE.md) file for more information.

[ico-version]: https://img.shields.io/packagist/v/egeatech/laravel-responses.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/egeatech/laravel-responses.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/egeatech/laravel-responses
[link-downloads]: https://packagist.org/packages/egeatech/laravel-responses
[link-travis]: https://travis-ci.org/egeatech/laravel-responses
[link-author]: https://egeatech.com
