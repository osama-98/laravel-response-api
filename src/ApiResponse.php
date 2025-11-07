<?php

namespace Osama\ApiResponse;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\CursorPaginator as ConcreteCursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator as ConcretePaginator;
use Illuminate\Support\Arr;
use InvalidArgumentException;
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
     *
     * @param  CursorPaginator<int, mixed>|Paginator<int, mixed>  $paginated
     */
    public static function pagination(
        CursorPaginator|Paginator $paginated,
        callable|string|null $mapper = null
    ): JsonResponse {
        /** @var LengthAwarePaginator<int, mixed>|ConcreteCursorPaginator<int, mixed>|ConcretePaginator<int, mixed> $paginated */
        $items = $paginated->items();

        if ($mapper !== null) {
            if (is_callable($mapper)) {
                $items = array_map(fn ($item) => $mapper($item), $items);
            } elseif (is_string($mapper) && class_exists($mapper) && method_exists($mapper, 'collection')) {
                $items = $mapper::collection($items);
            } else {
                throw new InvalidArgumentException('Invalid mapper provided');
            }
        }

        return self::builder()
            ->data([
                'data' => $items,
                'pagination' => Arr::except($paginated->jsonSerialize(), ['data']),
            ])
            ->send();
    }
}
