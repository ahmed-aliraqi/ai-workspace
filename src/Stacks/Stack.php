<?php

namespace AiWorkspace\Stacks;

abstract class Stack
{
    abstract public function key(): string;

    abstract public function label(): string;

    /** Stub filename inside stubs/stacks/, installed as .ai/stack.md */
    public function playbookStub(): string
    {
        return $this->key().'.md';
    }

    /**
     * Shell commands that install the stack's dependencies on the project.
     * Each entry: [description, command].
     *
     * @return array<int, array{0: string, 1: string}>
     */
    abstract public function setupSteps(): array;

    /** @return array<string, Stack> keyed by stack key */
    public static function all(): array
    {
        $stacks = [
            new InertiaVue(),
            new InertiaReact(),
            new ApiOnly(),
            new Blade(),
        ];

        $keyed = [];
        foreach ($stacks as $stack) {
            $keyed[$stack->key()] = $stack;
        }

        return $keyed;
    }
}
