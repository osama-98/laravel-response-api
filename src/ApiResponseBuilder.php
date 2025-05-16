<?php

namespace Osama\ApiResponse;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseBuilder
{
    /**
     * Response data
     *
     * @var mixed|null
     */
    private mixed $data = null;

    /**
     * Response message text
     */
    private ?string $message = null;

    /**
     * Error details or validation messages
     *
     * @var mixed|null
     */
    private mixed $errors = null;

    /**
     * HTTP status code
     */
    private int $status = Response::HTTP_OK;

    /**
     * Set response's data
     *
     * @return $this
     */
    public function data(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set response's message
     *
     * @return $this
     */
    public function message(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set error details or validation messages
     *
     * @return $this
     */
    public function errors(mixed $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Set response's status code
     *
     * @return $this
     */
    public function status(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Retrieve the first message or error
     */
    private function firstMessage(): ?string
    {
        if (! is_null($this->message)) {
            return $this->message;
        }

        if (! is_null($this->errors)) {
            $fieldError = Arr::first(Arr::wrap($this->errors));

            if (is_array($fieldError)) { // has multiple errors for a field
                return Arr::first($fieldError);
            }

            return $fieldError;
        }

        return null;
    }

    /**
     * Build and return the JSON response
     */
    public function send(): JsonResponse
    {
        return response()->json(
            data: array_filter([
                'message' => $this->firstMessage(),
                'body' => $this->data,
                'errors' => $this->errors,
            ], fn ($value): bool => ! is_null($value)),
            status: $this->status
        );
    }
}
