# Laravel API Response

[![Latest Version on Packagist](https://img.shields.io/packagist/v/osama-98/laravel-response-api.svg)](https://packagist.org/packages/osama-98/laravel-response-api)
[![Total Downloads](https://img.shields.io/packagist/dt/osama-98/laravel-response-api.svg)](https://packagist.org/packages/osama-98/laravel-response-api)
[![License](https://img.shields.io/packagist/l/osama-98/laravel-response-api.svg)](https://packagist.org/packages/osama-98/laravel-response-api)

A Laravel package that provides a fluent and expressive interface for creating standardized JSON API responses.

## Installation

You can install the package via composer:
```bash
composer require osama-98/laravel-response-api
```

## Requirements

- PHP ^8.0
- Laravel ^8.0

## Response Structure

All responses follow this standardized JSON structure:
```json5
{
    "message": "Human-readable message",
    "body": {
        // Response data (optional) 
    },
    "errors": {
        // Validation or error details (optional) 
    }
}
```

## Basic Usage

### Success Response
```php
return ApiResponse::success(
    data: ['user' => $user],
    message: 'User retrieved successfully'
);
```
Response:
```json5
{
    "message": "User retrieved successfully",
    "body": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        }
    }
}
```
### Error Response
```php
return ApiResponse::error(
    message: 'Validation failed.',
    errors: $validator->errors()->toArray()
);
```
Response:
```json5
{
    "message": "Validation failed.",
    "errors": {
        "email": [
            "The email field must be a valid email address.",
            "The selected email is invalid."
        ]
    }
}
```
## Pagination Support

### Using with Resource
```php
return ApiResponse::pagination(
    paginator: User::paginate(10),
    mapper: UserResource::class
);
```
### Using with Custom Mapper
```php
return ApiResponse::pagination(paginator: User::paginate(10), mapper: fn(User $user) => [
    'id' => $user->id,
    'name' => $user->name
]);
```

## Fluent Builder

For more complex responses, use the fluent builder:
```php
return ApiResponse::builder()
    ->data(['key' => 'value'])
    ->message('Custom message')
    ->errors(['field' => 'error message'])
    ->status(Response::HTTP_BAD_REQUEST)
    ->send();
```

## Testing
```bash
composer test
```
This runs:
- Code style checks (Laravel Pint)
- Static analysis (PHPStan)
- Unit tests (Pest PHP)
- Type coverage tests

## Security

If you discover any security-related issues, please email osama.sada98@gmail.com instead of using the issue tracker.

## Credits

- [Osama Sadah](https://github.com/osama-98)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
