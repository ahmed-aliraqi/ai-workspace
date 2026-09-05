<?php

namespace AiWorkspace\Stacks;

class Blade extends Stack
{
    public function key(): string
    {
        return 'blade';
    }

    public function label(): string
    {
        return 'Laravel + Blade (Tailwind + Alpine)';
    }

    public function setupSteps(): array
    {
        return [
            ['Install Tailwind CSS v4', 'npm install tailwindcss @tailwindcss/vite'],
            ['Install Alpine.js', 'npm install alpinejs'],
        ];
    }
}
