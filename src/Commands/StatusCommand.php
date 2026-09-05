<?php

namespace AiWorkspace\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class StatusCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('status')
            ->setDescription('Show the current sprint board of an installed workspace')
            ->addArgument('path', InputArgument::OPTIONAL, 'Path to the Laravel project', '.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $board = rtrim($input->getArgument('path'), '/').'/.ai/state/BOARD.md';

        if (! file_exists($board)) {
            $io->error('No workspace found (.ai/state/BOARD.md missing). Run `ai-workspace install` first.');

            return Command::FAILURE;
        }

        $io->writeln(file_get_contents($board));

        return Command::SUCCESS;
    }
}
