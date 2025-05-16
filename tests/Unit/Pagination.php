<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Osama\ApiResponse\ApiResponse;
use Osama\ApiResponse\Resources\FooResource;
use Tests\TestCase;

uses(TestCase::class);

test('pagination with length aware paginated', function (): void {
    $items = ['item1', 'item2'];
    $paginated = new LengthAwarePaginator(
        $items,
        count($items),
        1,
        1
    );

    $response = ApiResponse::pagination($paginated);

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getData(true))
        ->toHaveKey('body')
        ->and($response->getData(true)['body'])
        ->toHaveKeys(['data', 'pagination']);
});

test('pagination with cursor paginated', function (): void {
    $items = ['item1', 'item2'];
    $paginated = new CursorPaginator(
        $items,
        2
    );

    $response = ApiResponse::pagination($paginated);

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getData(true))
        ->toHaveKey('body')
        ->and($response->getData(true)['body'])
        ->toHaveKeys(['data', 'pagination']);
});

test('pagination with callable mapper', function (): void {
    $items = ['item1', 'item2'];
    $paginated = new Paginator($items, 2);
    $mapper = fn ($item) => strtoupper($item);

    $response = ApiResponse::pagination($paginated, $mapper);

    expect($response)
        ->toBeInstanceOf(JsonResponse::class)
        ->and($response->getData(true)['body']['data'])->toBe(['ITEM1', 'ITEM2']);
});

test('pagination with invalid mapper throws exception', function (): void {
    $items = ['item1', 'item2'];
    $paginated = new Paginator($items, 2);

    expect(fn (): JsonResponse => ApiResponse::pagination($paginated, 'InvalidMapper'))
        ->toThrow(InvalidArgumentException::class, 'Invalid mapper provided');
});

test('pagination with a json resource', function (): void {
    $items = ['item1', 'item2'];
    $paginated = new LengthAwarePaginator(
        $items,
        2,
        2,
        1
    );

    $response = ApiResponse::pagination(
        paginated: $paginated,
        mapper: FooResource::class
    );

    expect($response)
        ->toBeInstanceOf(JsonResponse::class);
});
