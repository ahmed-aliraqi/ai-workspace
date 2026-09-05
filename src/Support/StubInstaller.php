<?php

namespace AiWorkspace\Support;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Filesystem\Filesystem;

class StubInstaller
{
    /**
     * Stub directories are stored without the leading dot (some tools refuse to
     * write dot-paths); the dot is restored at install time.
     */
    private const RENAMES = [
        'claude/' => '.claude/',
        'github/' => '.github/',
    ];

    private Filesystem $files;

    /** @var string[] */
    private array $written = [];

    /** @var string[] */
    private array $skipped = [];

    public function __construct(
        private string $stubsPath,
        private string $projectPath,
        private array $replacements,
        private bool $force = false,
    ) {
        $this->files = new Filesystem();
    }

    /** Copy a stub directory tree into the project, applying replacements. */
    public function copyTree(string $stubDir): void
    {
        $base = rtrim($this->stubsPath, '/').'/'.trim($stubDir, '/');

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($base, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $relative = substr($file->getPathname(), strlen($base) + 1);

            foreach (self::RENAMES as $from => $to) {
                if (str_starts_with($relative, $from)) {
                    $relative = $to.substr($relative, strlen($from));
                    break;
                }
            }

            $this->copyFile($file->getPathname(), $relative);
        }
    }

    /** Copy a single stub file to a target path relative to the project root. */
    public function copyFile(string $source, string $target): void
    {
        $destination = rtrim($this->projectPath, '/').'/'.$target;

        if ($this->files->exists($destination) && ! $this->force) {
            $this->skipped[] = $target;

            return;
        }

        $content = strtr(file_get_contents($source), $this->replacements);

        $this->files->mkdir(dirname($destination));
        $this->files->dumpFile($destination, $content);

        $this->written[] = $target;
    }

    /** Append lines to a project file, creating it if missing, skipping lines already present. */
    public function appendUnique(string $target, array $lines): void
    {
        $destination = rtrim($this->projectPath, '/').'/'.$target;
        $existing = $this->files->exists($destination) ? file_get_contents($destination) : '';

        $missing = array_filter($lines, fn ($line) => ! str_contains($existing, $line));

        if ($missing === []) {
            return;
        }

        $suffix = ($existing !== '' && ! str_ends_with($existing, "\n") ? "\n" : '').implode("\n", $missing)."\n";
        $this->files->dumpFile($destination, $existing.$suffix);

        $this->written[] = $target;
    }

    /** @return string[] */
    public function written(): array
    {
        return $this->written;
    }

    /** @return string[] */
    public function skipped(): array
    {
        return $this->skipped;
    }
}
