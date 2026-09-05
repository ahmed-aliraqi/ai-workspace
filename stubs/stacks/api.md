# Stack playbook — Laravel API only (Sanctum)

## Structure

```
app/Http/Controllers/Api/V1/   # versioned API controllers, thin
app/Http/Resources/            # JsonResource per model — the ONLY response shape
app/Http/Requests/             # FormRequest per write endpoint
app/Services/                  # business logic
routes/api.php                 # shared file — Tariq only (or one route file per module)
```

## Wiring (once, after installer --setup)

1. `php artisan install:api` already ran (routes/api.php + Sanctum).
2. Version the API: prefix `v1` route group, controllers under `Api\V1`.
3. Force JSON: middleware or `$request->expectsJson()` handling in exceptions
   (`bootstrap/app.php` -> `->withExceptions()` renders JSON for `api/*`).
4. Scribe config (`config/scribe.php`) for generated API docs: `php artisan scribe:generate`.

## Conventions

- Every response goes through a `JsonResource` / `ResourceCollection` — never raw models.
  Consistent envelope: resources + `meta` for pagination.
- Auth: Sanctum tokens; abilities for scopes. Every endpoint has a policy check —
  QA treats a missing authorization test as a defect.
- Validation in FormRequests; errors are Laravel's standard 422 shape.
- Status codes: 201 + resource on create, 204 on delete, 404 via route model binding,
  403 from policies. No 200-with-error-body responses.
- Rate limiting on auth and write endpoints (`throttle`).
- N+1: resources declare `whenLoaded`; controllers eager-load exactly what the
  resource needs.
- The task body pins each endpoint's contract: method, URI, request fields + rules,
  response JSON example, error cases. Scribe annotations on every controller method.

## Checks a task must pass

Pest feature tests per endpoint: happy path, validation failure (422), unauthorized (401),
forbidden (403), not found (404). `php artisan scribe:generate` must run clean.

## Manual test script style (for PRs)

Use curl or the generated Scribe docs UI: exact requests with headers and bodies,
expected status + JSON.
