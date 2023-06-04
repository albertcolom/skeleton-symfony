<?php

declare(strict_types=1);

namespace App\UI\Command;

use App\Context\Foo\Application\Service\FooIndexUpdater;
use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\Write\Repository\FooRepository;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class PopulateFooCommand extends Command
{
    protected static $defaultName = 'foo:search:full-import-data';

    public function __construct(
        private readonly FooRepository $fooRepository,
        private readonly Client $client,
        private readonly FooIndexUpdater $fooIndexUpdater,
        private readonly string $fooIndex
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Sync foo with elasticsearch')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force re-create index');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('force')) {
            $this->deleteIndex();
        }

        $this->fooRepository->findAll()->each(function (int $key, Foo $foo) use ($output) {
            $output->writeln(sprintf('<info>Indexing: %s</info>', $foo->id->value));
            $this->fooIndexUpdater->execute($foo->id);
            return $foo;
        });

        return Command::SUCCESS;
    }

    private function deleteIndex(): void
    {
        try {
            $this->client->indices()->delete(['index' => $this->fooIndex]);
        } catch (ClientResponseException $e) {
            if ($e->getCode() !== 404) {
                throw $e;
            }
        }
    }
}
