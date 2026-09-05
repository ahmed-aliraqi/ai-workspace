<?php

namespace AiWorkspace\Stacks;

class InertiaReact extends Stack
{
    public function key(): string
    {
        return 'inertia-react';
    }

    public function label(): string
    {
        return 'Laravel + Inertia + React (shadcn/ui)';
    }

    public function setupSteps(): array
    {
        return [
            ['Install Inertia server-side adapter', 'composer require inertiajs/inertia-laravel'],
            ['Install Ziggy for route helpers', 'composer require tightenco/ziggy'],
            ['Install React + Inertia client', 'npm install react react-dom @inertiajs/react @vitejs/plugin-react'],
            ['Install Tailwind CSS v4', 'npm install tailwindcss @tailwindcss/vite'],
            ['Install TypeScript toolchain', 'npm install -D typescript @types/react @types/react-dom @types/node'],
            ['Install utility deps used by shadcn/ui', 'npm install class-variance-authority clsx tailwind-merge lucide-react'],
        ];
    }
}
