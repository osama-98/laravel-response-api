# ApiResponse Utility Class

The `ApiResponse` class in the `App\Utilities\ApiResponse` namespace provides a fluent, expressive interface for creating standardized JSON API responses.

---

## Structure

All responses follow this general JSON structure:

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

### Response with data
```json5
{
    "message": "Human-readable message",
    "body": {
        "id": 1,
        "name": 'John Doe',
        "age": 25,
        "address": "Cecilia Chapman 711-2880 St. Mankato Mississippi 96522 (257) 563-7401"
    }
}
```

### Response with errors
```json5
{
    "message": "Human-readable message",
    "errors": {
        "email": [
            "The email field must be a valid email address.",
            "The selected email is invalid."
        ],
        "first_name": [
            "The first name field is required.",
            "The first name field max length is 255."
        ],
        "last_name": [
            "The last name field is required.",
            "The last name field max length is 255."
        ],
        "address": [
            "The address field must be a valid email address"
        ]
    }
}
```
---

## Basic Usage

### Success Response
```php
return ApiResponse::success(message: __('messages.course_activated'));
```

### Response (status code: 200 Ok)
```json5
{
    "message": "Course activated successfully."
}
```
---

### Success Response With Data
```php
return ApiResponse::success(data: CourseResource::make($course), message: __('messages.course_activated'));
```
### Response (status code: 200 Ok)
`/api/v1/courses/statistics-1` (Show course by slug)
```json5
{
    "message": "Course activated successfully.",
    "body": {
        "id": 1,
        "name": "Statistics 1"
        // other resource properties
    }
}
```
---

### Error Response
```php
return ApiResponse::error(message: __('messages.resource_not_exists', ['resource' => 'course']));
```

### Response (status code: 400 Bad Request)
```json5
{
    "message": "course does not exist!"
}
```
---

### Error response with errors

```php
return ApiResponse::error(
    errors: [
        'email' => [
            __('validation.email', ['attribute' => 'email']),
            __('validation.exists', ['attribute' => 'email'])
        ]
    ]
);
```
### Response (status code: 400 Bad Request)
```json5
{
    "message": "The email field must be a valid email address.", // The first message became the response message
    "errors": {
        "email": [
            "The email field must be a valid email address.",
            "The selected email is invalid."
        ]
    }
}
```
---

### Error response with errors and custom message

```php
return ApiResponse::error(
    errors: [
        'email' => [
            __('validation.email', ['attribute' => 'email']),
            __('validation.exists', ['attribute' => 'email'])
        ]
    ],
    message: __('messages.email_not_found_or_invalid')
);
```
### Response (status code: 400 Bad Request)
```json5
{
    "message": "The email field is invalid or not found in our records.",
    "errors": {
        "email": [
            "The email field must be a valid email address.",
            "The selected email is invalid."
        ]
    }
}
```
---

### Created resource response

```php
return ApiResponse::created(data: CourseResource::make($course), attribute: __('attributes.course'));
```

### Response (status code: 201 Created)
```json5
{
    "message": "course has been created successfully.",
    "body": {
        "id": 1,
        "name": "Statistics 1"
        // other resource properties
    }
}
```
---

### Updated Resource Response

```php
return ApiResponse::updated(data: CourseResource::make($course), attribute: __('attributes.course'));
```
### Response (status code: 200 Ok)
```json5
{
    "message": "course has been updated successfully.",
    "body": {
        "id": 1,
        "name": "Statistics 1"
        // other resource properties
    }
}
```
---

### Deleted Resource Response

```php
return ApiResponse::destroyed(condition: $course->delete(), attribute: __('attributes.course'));
```

### Response (status code: 200 Ok)
```json5
{
    "message": "course has been deleted successfully."
}
```
---

## Custom Response (Fluent)

```php
return ApiResponse::builder()
    ->data(['some' => ['data']])
    ->message('Custom message')
    ->errors(['extra' => 'info'])
    ->status(Response::HTTP_ACCEPTED)
    ->send();
```

### Response (status code: 202 Accepted)
```json5
{
    "message": "Custom message",
    "body": {
        "some": [
            "data"
        ]
    },
    "errors": {
        "extra": "info"
    }
}
```

---

## Pagination

Supports `Paginator`, `LengthAwarePaginator`, and `CursorPaginator`.

### With a Resource Collection

```php
return ApiResponse::pagination(paginator: User::paginate(10), resource: UserResource::class);
```

### Response
```json5
{
    "body": {
        "data": [
            {
                "id": 1,
                "name": "Statistics 1",
                // other resource properties
            },
            // ...
        ],
        "pagination": {
            "current_page": 1,
            "first_page_url": "https://kalemon.joacademy.com/api/v1/courses?page=1",
            "from": 1,
            "last_page": 10,
            "last_page_url": "https://kalemon.joacademy.com/api/v1/courses?page=10",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "https://kalemon.joacademy.com/api/v1/courses?page=1",
                    "label": "1",
                    "active": true
                },
                // more links
                {
                    "url": "https://kalemon.joacademy.com/api/v1/courses?page=2",
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "next_page_url": "https://kalemon.joacademy.com/api/v1/courses?page=2",
            "path": "https://kalemon.joacademy.com/api/v1/courses",
            "per_page": 10,
            "prev_page_url": null,
            "to": 10,
            "total": 100
        }
    }
}
```

### Without a Resource

```php
return ApiResponse::pagination(paginator: Course::cursorPaginate());
```
### Response
```json5
{
    "body": {
        "data": [
            {
                "id": 1,
                "name": "Statistics 1",
                // other resource properties
            },
            // ...
        ],
        "pagination": {
            "path": "https://kalemon.joacademy.com/api/v1/courses",
            "per_page": 1,
            "next_cursor": "eyJjb3Vyc2VzLmLkIjOxLCJfcG9pbnRzVG9OZXh0SXRlbXMiOnRydWV9",
            "next_page_url": "https://kalemon.joacademy.com/api/v1/courses?cursor=eyJjb3Vyc2VzLmLkIjOxLCJfcG9pbnRzVG9OZXh0SXRlbXMiOnRydWV9",
            "prev_cursor": null,
            "prev_page_url": null
        }
    }
}
```
---

### With Custom Mapper

```php
// 1. using closure directly
return ApiResponse::pagination(paginator: Course::simplePaginate(), mapper: fn($course) => [
    'id' => $course->id,
    'status' => $course->status,
    'custom_key' => $course->custom_value
]);

// 2. using callable methods
return ApiResponse::pagination(paginator: Course::simplePaginate(), mapper: $this->mapCourse(...));

public function mapCourse(Course $course): array
{
    return [
        'id' => $course->id,
        'status' => $course->status,
        'custom_key' => $course->custom_value
    ];
}

// 3. using invokable class as a mapper
return ApiResponse::pagination(paginator: Course::simplePaginate(), mapper: new CustomCourseMapper());

class CustomCourseMapper
{
    public function __invoke(Course $course): array
    {
        return [
            'id' => $course->id,
            'status' => $course->status,
            'custom_key' => $course->custom_value
        ];
    }
}
```

---

## Error Only Response

```php
return ApiResponse::builder()
    ->message('Something went wrong')
    ->asServerError()
    ->send();
```

### Response
```json5
{
    "message": "Something went wrong"
}
```

---

## Notes

- `message()` and `errors()` are both optional; if both are set, `message` takes precedence.
- Pagination data includes metadata such as `current_page`, `last_page`, etc., except for the actual `data`.
