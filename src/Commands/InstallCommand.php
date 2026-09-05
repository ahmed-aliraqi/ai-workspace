<?php

namespace AiWorkspace\Commands;

use AiWorkspace\Stacks\Stack;
use AiWorkspace\Support\StubInstaller;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('install')
            ->setDescription('Install the AI multi-agent workspace into a Laravel project')
            ->addArgument('path', InputArgument::OPTIONAL, 'Path to the Laravel project', '.')
            ->addOption('stack', 's', InputOption::VALUE_REQUIRED, 'Stack: inertia-vue, inertia-react, api, blade')
            ->addOption('setup', null, InputOption::VALUE_NONE, 'Also run the stack dependency installation commands')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Overwrite existing workspace files');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $projectPath = realpath($input->getArgument('path')) ?: $input->getArgument('path');

        $io->title('AI Workspace Installer');

        if (! is_dir($projectPath)) {
            $io->error("Directory not found: {$projectPath}");

            return Command::FAILURE;
        }

        if (! file_exists($projectPath.'/artisan')) {
            $io->error("No artisan file found in {$projectPath} — this does not look like a Laravel project.");
            $io->text('Create the project first (e.g. `laravel new myapp`) then run the installer inside it.');

            return Command::FAILURE;
        }

        $stacks = Stack::all();
        $stackKey = $input->getOption('stack');

        if ($stackKey === null) {
            $labels = array_map(fn (Stack $s) => $s->label(), $stacks);
            $chosenLabel = $io->choice('Which stack is this project using?', array_values($labels), $labels['inertia-vue']);
            $stackKey = array_search($chosenLabel, $labels, true);
        }

        if (! isset($stacks[$stackKey])) {
            $io->error("Unknown stack '{$stackKey}'. Valid options: ".implode(', ', array_keys($stacks)));

            return Command::FAILURE;
        }

        $stack = $stacks[$stackKey];
        $projectName = basename($projectPath);

        $installer = new StubInstaller(
            stubsPath: dirname(__DIR__, 2).'/stubs',
            projectPath: $projectPath,
            replacements: [
                '{{PROJECT_NAME}}' => $projectName,
                '{{STACK_KEY}}' => $stack->key(),
                '{{STACK_LABEL}}' => $stack->label(),
                '{{DATE}}' => date('Y-m-d'),
            ],
            force: (bool) $input->getOption('force'),
        );

        $installer->copyTree('common');
        $installer->copyFile(dirname(__DIR__, 2).'/stubs/stacks/'.$stack->playbookStub(), '.ai/stack.md');
        $installer->appendUnique('.gitignore', ['/.worktrees', '.ai/state/scratch/']);

        foreach ($installer->written() as $file) {
            $io->writeln("  <info>created</info>  {$file}");
        }

        foreach ($installer->skipped() as $file) {
            $io->writeln("  <comment>skipped</comment>  {$file} (exists, use --force to overwrite)");
        }

        if ($input->getOption('setup')) {
            $this->runSetup($io, $stack, $projectPath);
        }

        $io->success("AI workspace installed with stack: {$stack->label()}");
        $io->section('Next steps');
        $io->listing([
            'Fill in .ai/state/PROJECT.md with the project goal and modules.',
            $input->getOption('setup') ? 'Stack dependencies installed — review .ai/stack.md for wiring steps.' : 'Run stack setup: ai-workspace install --stack='.$stack->key().' --setup (or follow .ai/stack.md manually).',
            'Ensure branches exist: git checkout -b develop (from main).',
            'Open Claude Code in the project and run /sprint-plan to plan Sprint 1.',
        ]);

        return Command::SUCCESS;
    }

    private function runSetup(SymfonyStyle $io, Stack $stack, string $projectPath): void
    {
        $io->section('Installing stack dependencies');

        foreach ($stack->setupSteps() as [$description, $command]) {
            $io->writeln("  <info>{$description}</info>");
            $io->writeln("  <comment>$ {$command}</comment>");

            $process = Process::fromShellCommandline($command, $projectPath, timeout: 600);
            $process->run(fn ($type, $buffer) => $io->write($buffer));

            if (! $process->isSuccessful()) {
                $io->warning("Command failed: {$command} — continue manually, see .ai/stack.md");
            }
        }
    }
}
