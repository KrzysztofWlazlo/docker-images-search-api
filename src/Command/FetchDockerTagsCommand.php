<?php

namespace App\Command;

use App\Service\DockerService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:fetch-docker-tags',
    description: 'Fetches Docker tags and updates the local database.'
)]
class FetchDockerTagsCommand extends Command
{
    private DockerService $dockerService;

    public function __construct(DockerService $dockerService)
    {
        parent::__construct();
        $this->dockerService = $dockerService;
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->dockerService->updateTags();

        $output->writeln('Docker tags updated successfully.');

        return Command::SUCCESS;
    }
}
