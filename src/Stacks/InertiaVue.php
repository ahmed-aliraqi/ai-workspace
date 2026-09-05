<?php

namespace AiWorkspace\Stacks;

class InertiaVue extends Stack
{
    public function key(): string
    {
        return 'inertia-vue';
    }

    public function label(): string
    {
        return 'Laravel + Inertia + Vue (shadcn-vue)';
    }

    public function setupSteps(): array
    {
        return [
            ['Install Inertia server-side adapter', 'composer require inertiajs/inertia-laravel'],
            ['Install Ziggy for route helpers', 'composer require tightenco/ziggy'],
            ['Install Vue + Inertia client', 'npm install vue @inertiajs/vue3 @vitejs/plugin-vue'],
            ['Install Tailwind CSS v4', 'npm install tailwindcss @tailwindcss/vite'],
            ['Install shadcn-vue prerequisites', 'npm install -D typescript vue-tsc @types/node'],
            ['Install utility deps used by shadcn-vue', 'npm install class-variance-authority clsx tailwind-merge lucide-vue-next reka-ui'],
        ];
    }
}
