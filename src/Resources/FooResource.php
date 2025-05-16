<?php

namespace Osama\ApiResponse\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FooResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'created_at' => '2024-01-01 00:00:00',
        ];
    }
}
