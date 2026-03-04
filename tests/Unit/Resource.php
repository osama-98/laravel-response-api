<?php

use Illuminate\Http\JsonResponse;
use Osama\ApiResponse\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

uses(TestCase::class);

// created()

test('created response with resource name', function (): void {
    $response = ApiResponse::created(resource: 'User');

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_CREATED)
        ->and($response->getData(true))->toBe([
            'message' => 'User created successfully.',
        ]);
});

test('created response with custom message', function (): void {
    $response = ApiResponse::created(message: 'Custom create message');

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_CREATED)
        ->and($response->getData(true))->toBe([
            'message' => 'Custom create message',
        ]);
});

test('created response with data', function (): void {
    $response = ApiResponse::created(data: ['id' => 1], resource: 'User');

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_CREATED)
        ->and($response->getData(true))->toBe([
            'message' => 'User created successfully.',
            'body' => ['id' => 1],
        ]);
});

// updated()

test('updated response with resource name', function (): void {
    $response = ApiResponse::updated(data: null, resource: 'User');

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_OK)
        ->and($response->getData(true))->toBe([
            'message' => 'User updated successfully.',
        ]);
});

test('updated response with custom message', function (): void {
    $response = ApiResponse::updated(data: null, message: 'Custom update message');

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_OK)
        ->and($response->getData(true))->toBe([
            'message' => 'Custom update message',
        ]);
});

test('updated response with data', function (): void {
    $response = ApiResponse::updated(data: ['id' => 1], resource: 'User');

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_OK)
        ->and($response->getData(true))->toBe([
            'message' => 'User updated successfully.',
            'body' => ['id' => 1],
        ]);
});

// destroyed()

test('destroyed response when successful', function (): void {
    $response = ApiResponse::destroyed(destroyed: true, resource: 'User');

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_OK)
        ->and($response->getData(true))->toBe([
            'message' => 'User deleted successfully.',
        ]);
});

test('destroyed response when not found uses default message', function (): void {
    $response = ApiResponse::destroyed(destroyed: false, resource: 'User');

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_BAD_REQUEST)
        ->and($response->getData(true))->toBe([
            'message' => 'User not found.',
        ]);
});

test('destroyed response when not found uses custom message', function (): void {
    $response = ApiResponse::destroyed(destroyed: false, message: 'Could not delete resource');

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_BAD_REQUEST)
        ->and($response->getData(true))->toBe([
            'message' => 'Could not delete resource',
        ]);
});
