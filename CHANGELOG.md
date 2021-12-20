# Changelog

All notable changes to `LaravelResponses` will be documented in this file.

## Unreleased

## 2.1.0
### Added
- Allowed to send to the user unformatted data (except for Model and Collection instances)

## Version 2.0.3
### Fixed
- PHP version in `composer.json` file

## Version 2.0.2
### Fixed
- Syntax error in `*Response` classes

## Version 2.0.1
### Updated
- `README.md` file to describe new package usage

## Version 2.0.0
### Updated
- Backport library support also for Laravel 6.x
- Removed incoming request dependency in `*Response` classes
- Allowed collection of elements to be accepted by `ApiResponse` class
- `$status` parameter is now `$httpStatus`
- `$jsonResource` parameter is now `$responseFormatter`. Its type was changed from `JsonResource` to `string`, but is 
expected to be a `JsonResource` instantiable class.

## Version 1.0.3
### Fixed
- PHP version in `composer.json` file

## Version 1.0.2
### Fixed
- Namespaces issues

## Version 1.0.1
### Fixed
- `composer.json` issues

## Version 1.0
### Added
- `ApiResponse` and `PaginatedApiResponse` classes 
