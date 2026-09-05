# Stack playbook — Laravel + Blade (Tailwind + Alpine)

## Structure

```
resources/views/
  layouts/app.blade.php        # main layout (shared file — Tariq only)
  components/                  # Blade components (anonymous or class-based)
  <module>/                    # views per module (invoices/index.blade.php)
resources/css/app.css          # Tailwind entry
resources/js/app.js            # Alpine bootstrap (shared file — Tariq only)
routes/web.php                 # shared file — Tariq only (or one route file per module)
```

## Wiring (once, after installer --setup)

1. `vite.config.js`: `laravel-vite-plugin` + `@tailwindcss/vite`.
2. `resources/css/app.css`: `@import "tailwindcss";`.
3. `resources/js/app.js`: `import Alpine from 'alpinejs'; window.Alpine = Alpine; Alpine.start();`.
4. Layout with `@vite(['resources/css/app.css','resources/js/app.js'])`.

## Conventions

- Blade components for every repeated UI element (`<x-button>`, `<x-input>`,
  `<x-modal>`, `<x-table>`); props typed via `@props`. No copy-pasted markup.
- Interactivity: Alpine only (`x-data`, `x-show`, `x-on`) — no jQuery, no inline JS.
  Complex widgets get a dedicated Alpine component in `resources/js/`.
- Forms: standard POST + redirect; old input via `old()`; errors via
  `@error` under every field; flash messages in the layout.
- Controllers: thin, return views with explicit data; paginate with `links()`.
- CSRF on every form (`@csrf`); method spoofing (`@method`) where needed.
- Authorization: `@can` in views AND policy checks in controllers (views are not security).

## Checks a task must pass

Pest feature tests per route: page renders (200, sees key text), form validation errors,
authorization. `npm run build` green.

## Manual test script style (for PRs)

Browser steps: URL, what to click/type, what must appear — including a validation
failure case and a forbidden case.
