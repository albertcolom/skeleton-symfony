<?php

declare(strict_types=1);

namespace App\UI\Command;

use App\Context\Foo\Application\Service\FooIndexUpdater;
use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\Repository\Write\FooRepository;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateFooCommand extends Command
{
    protected static $defaultName = 'foo:search:full-import-data';

    public function __construct(
        private FooRepository $fooRepository,
        private Client $client,
        private FooIndexUpdater $fooIndexUpdater,
        private string $fooIndex
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
            $output->writeln(sprintf('<info>Indexing: %s</info>', $foo->fooId()->value()));
            $this->fooIndexUpdater->execute($foo->fooId());
            return $foo;
        });

        return Command::SUCCESS;
    }

    private function deleteIndex(): void
    {
        try {
            $this->client->indices()->delete(['index' => $this->fooIndex]);
        } catch (Missing404Exception) {
            return;
        }
    }
}
