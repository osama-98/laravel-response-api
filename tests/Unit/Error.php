<?php

use Illuminate\Http\JsonResponse;
use Osama\ApiResponse\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

uses(TestCase::class);

test('error response with message only', function (): void {
    $response = ApiResponse::error(message: 'Error message');

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_BAD_REQUEST)
        ->and($response->getData(true))->toBe([
            'message' => 'Error message',
        ]);
});

test('error response with validation errors', function (): void {
    $errors = [
        'email' => ['The email field is required.'],
        'password' => ['The password field is required.'],
    ];

    $response = ApiResponse::error(errors: $errors);

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_BAD_REQUEST)
        ->and($response->getData(true))->toBe([
            'message' => 'The email field is required.',
            'errors' => $errors,
        ]);
});

test('error response with validation errors and the field errors is string not an array', function (): void {
    $errors = [
        'email' => 'The email field is required.',
        'password' => 'The password field is required.',
    ];

    $response = ApiResponse::error(errors: $errors);

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_BAD_REQUEST)
        ->and($response->getData(true))->toBe([
            'message' => 'The email field is required.',
            'errors' => $errors,
        ]);
});

test('error response with custom status code', function (): void {
    $response = ApiResponse::error(
        message: 'Not Found',
        status: Response::HTTP_NOT_FOUND
    );

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_NOT_FOUND)
        ->and($response->getData(true))->toBe([
            'message' => 'Not Found',
        ]);
});
