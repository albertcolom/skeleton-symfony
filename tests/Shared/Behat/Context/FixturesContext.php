<?php

declare(strict_types=1);

namespace App\Tests\Shared\Behat\Context;

use Behat\Behat\Context\Context;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

final class FixturesContext implements Context
{
    private Application $application;
    private BufferedOutput $output;

    public function __construct(KernelInterface $kernel)
    {
        $this->application = new Application($kernel);
        $this->output = new BufferedOutput();
    }

    /**
     * @BeforeScenario
     */
    public function rebuildTestDataBase(): void
    {
        $this->dropDataBase();
        $this->createDataBase();
        $this->executeMigrations();
    }

    /**
     * @AfterScenario
     */
    public function removeDataBase(): void
    {
        $this->dropDataBase();
    }

    /**
     * @Given I load fixtures
     */
    public function iLoadFixtures(): void
    {
        $input = new ArrayInput([
            'command' => 'doctrine:fixtures:load',
            '--env' => 'test',
        ]);
        $input->setInteractive(false);
        $this->application->doRun($input, $this->output);
    }

    /**
     * @Given I load fixtures for groups :groups
     */
    public function iLoadFixturesForGroups(string $groups): void
    {
        $input = new ArrayInput([
            'command' => 'doctrine:fixtures:load',
            '--group' => explode(',', $groups),
            '--env' => 'test',
        ]);
        $input->setInteractive(false);
        $this->application->doRun($input, $this->output);
    }

    private function dropDataBase(): void
    {
        $input = new ArrayInput([
            'command' => 'doctrine:database:drop',
            '--force' => true,
            '--if-exists' => true,
            '--env' => 'test',
        ]);
        $input->setInteractive(false);
        $this->application->doRun($input, $this->output);
    }

    private function createDataBase(): void
    {
        $input = new ArrayInput([
            'command' => 'doctrine:database:create',
            '--if-not-exists' => true,
            '--env' => 'test',
        ]);
        $input->setInteractive(false);
        $this->application->doRun($input, $this->output);
    }

    private function executeMigrations(): void
    {
        $input = new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--env' => 'test',
        ]);
        $input->setInteractive(false);
        $this->application->doRun($input, $this->output);
    }
}
