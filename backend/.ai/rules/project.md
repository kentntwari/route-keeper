# Route Keeper Project Rules

These rules apply across the repository unless a narrower rule file overrides them.

## Architecture

- Unless otherwise directed, write code against Laravel 12 conventions and APIs.

## Security

- Never commit `.env` files or other secret-bearing environment files.
- Never trust data supplied over HTTP.
- Validate all user input with Form Requests.
- Enforce authorization with policies on every resource.
- Protect models from mass assignment with explicit `$fillable` or `$guarded` declarations.

## Laravel Backend

- Keep controllers thin.
- Move business logic out of controllers into dedicated services when the logic is not simple request orchestration.
- Follow Laravel conventions before introducing custom patterns.
- Prefer Eloquent and framework features over raw database access unless there is a clear, approved reason.

## Database

- Model write paths must account for mass-assignment protection.
- Schema and persistence changes should preserve Laravel naming and migration conventions.

## API

- Treat every request as untrusted input.
- Apply validation and authorization consistently on API endpoints.
- Unless otherwise noted, use Laravel JSON resources for API responses.

## Testing

- Add or update tests for behavior changes that affect requests, authorization, validation, or persistence.
- Prefer feature tests for HTTP behavior.
- Run `php artisan larastan:analyse` whenever changes are made.