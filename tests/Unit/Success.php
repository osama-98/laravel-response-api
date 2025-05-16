<?php

use Illuminate\Http\JsonResponse;
use Osama\ApiResponse\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

uses(TestCase::class);

test('success response with message only', function (): void {
    $response = ApiResponse::success(message: 'SuccessFeature message');

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_OK)
        ->and($response->getData(true))->toBe([
            'message' => 'SuccessFeature message',
        ]);
});

test('success response with data only', function (): void {
    $data = ['key' => 'value'];
    $response = ApiResponse::success(data: $data);

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_OK)
        ->and($response->getData(true))->toBe([
            'body' => ['key' => 'value'],
        ]);
});

test('success response with data and message', function (): void {
    $data = ['key' => 'value'];
    $response = ApiResponse::success(
        data: $data,
        message: 'SuccessFeature message'
    );

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_OK)
        ->and($response->getData(true))->toBe([
            'message' => 'SuccessFeature message',
            'body' => ['key' => 'value'],
        ]);
});

test('success response with custom status code', function (): void {
    $response = ApiResponse::success(
        message: 'Created successfully',
        status: Response::HTTP_CREATED
    );

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_CREATED)
        ->and($response->getData(true))->toBe([
            'message' => 'Created successfully',
        ]);
});
