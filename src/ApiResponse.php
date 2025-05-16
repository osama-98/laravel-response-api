<?php

namespace Osama\ApiResponse;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

final class ApiResponse
{
    /**
     * Create a new ApiResponseBuilder instance
     */
    public static function builder(): ApiResponseBuilder
    {
        return new ApiResponseBuilder;
    }

    /**
     * Create a successful JSON response.
     */
    public static function success(mixed $data = null, ?string $message = null, int $status = Response::HTTP_OK): JsonResponse
    {
        return self::builder()
            ->message($message)
            ->data($data)
            ->status($status)
            ->send();
    }

    /**
     * Create an error JSON response.
     */
    public static function error(mixed $errors = null, ?string $message = null, int $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return self::builder()
            ->message($message)
            ->errors($errors)
            ->status($status)
            ->send();
    }

    /**
     * Prepare paginated data response.
     */
    public static function pagination(LengthAwarePaginator|CursorPaginator|Paginator $paginator, callable|string|null $mapper = null): JsonResponse
    {
        $items = $paginator->items();

        if ($mapper) {
            if (is_callable($mapper)) {
                $items = array_map(fn ($item) => $mapper($item), $items);
            } elseif (method_exists($mapper, 'collection')) {
                $items = $mapper::collection($items); // Use the provided resource class. (e.g., UserResource)
            }
        }

        return self::builder()
            ->data([
                'data' => $items,
                'pagination' => Arr::except($paginator->toArray(), ['data']),
            ])
            ->send();
    }

    /**
     * Generate a JSON response for a newly created resource.
     */
    public static function created(mixed $data, ?string $attribute = null, ?string $message = null, bool $plural = false): JsonResponse
    {
        return self::builder()
            ->data($data)
            ->message($message ?: self::resourceMessage(action: 'created', attribute: $attribute, plural: $plural))
            ->status(Response::HTTP_CREATED)
            ->send();
    }

    /**
     * Generate a JSON response for an updated resource.
     */
    public static function updated(mixed $data, ?string $attribute = null, ?string $message = null, bool $plural = false): JsonResponse
    {
        return self::builder()
            ->data($data)
            ->message($message ?: self::resourceMessage(action: 'updated', attribute: $attribute, plural: $plural))
            ->send();
    }

    /**
     * Generate a JSON response for destroyed resource.
     */
    public static function destroyed(bool $condition, ?string $attribute = null, ?string $message = null, bool $plural = false): JsonResponse
    {
        if ($condition) {
            return self::builder()
                ->message($message ?: self::resourceMessage(action: 'deleted', attribute: $attribute, plural: $plural))
                ->send();
        }

        return self::builder()
            ->message($message ?: __('messages.resource_not_found', ['resource' => $attribute]))
            ->status(Response::HTTP_BAD_REQUEST)
            ->send();
    }

    /**
     * Generate a resource message based on the given action, attribute, and plurality.
     */
    private static function resourceMessage(string $action, ?string $attribute, bool $plural): string
    {
        return $plural
            ? __("messages.resources_$action", ['resources' => $attribute])
            : __("messages.resource_$action", ['resource' => $attribute]);
    }
}
