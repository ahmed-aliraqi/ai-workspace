<?php

namespace AiWorkspace\Stacks;

class ApiOnly extends Stack
{
    public function key(): string
    {
        return 'api';
    }

    public function label(): string
    {
        return 'Laravel API only (Sanctum)';
    }

    public function setupSteps(): array
    {
        return [
            ['Scaffold API routes and install Sanctum', 'php artisan install:api --no-interaction'],
            ['Install Scribe for API documentation', 'composer require --dev knuckleswtf/scribe'],
        ];
    }
}
