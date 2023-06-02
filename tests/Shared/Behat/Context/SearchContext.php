<?php

declare(strict_types=1);

namespace App\Tests\Shared\Behat\Context;

use Behat\Behat\Context\Context;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

final class SearchContext implements Context
{
    private Application $application;
    private BufferedOutput $output;

    public function __construct(KernelInterface $kernel, private Client $client, private string $fooIndex)
    {
        $this->application = new Application($kernel);
        $this->output = new BufferedOutput();
    }

    /**
     * @BeforeScenario
     */
    public function removeIndex(): void
    {
        $this->removeFooIndex();
    }

    /**
     * @Given I index foo search
     */
    public function iIndexFooSearch(): void
    {
        $input = new ArrayInput([
            'command' => 'foo:search:full-import-data',
        ]);
        $input->setInteractive(false);
        $this->application->doRun($input, $this->output);
    }

    /**
     * @Then I wait to index :total foo data
     */
    public function iWaitToIndexTotalFoo(int $total): void
    {
        while ($total > $this->client->count(['index' => $this->fooIndex])['count']){
            $this->iWaitToIndexTotalFoo($total);
        }
    }

    private function removeFooIndex(): void
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
