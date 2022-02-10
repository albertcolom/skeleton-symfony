<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Command\Create;

use App\Context\Foo\Application\Command\Create\CreateFooCommand;
use App\Context\Foo\Application\Command\Create\CreateFooCommandHandler;
use App\Context\Foo\Application\Command\Create\CreateFooService;
use App\Tests\Shared\Context\Foo\Domain\FooIdStub;
use App\Tests\Shared\Context\Foo\Domain\FooStub;
use PHPUnit\Framework\TestCase;

class CreateFooCommandHandlerTest extends TestCase
{
    private CreateFooService $createFooService;
    private CreateFooCommand|null $command;

    protected function setUp(): void
    {
        $this->createFooService = $this->createMock(CreateFooService::class);
        $this->command = null;
    }

    public function testItShouldCreateFoo(): void
    {
        $this->givenCreateFooCommand();
        $this->thenFooIsCreated();
        $this->whenTheCommandHandlerIsInvoked();
    }

    private function givenCreateFooCommand(): void
    {
        $this->command = new CreateFooCommand(FooIdStub::DEFAULT_FOO_ID, FooStub::DEFAULT_FOO_NAME);
    }

    private function thenFooIsCreated(): void
    {
        $this->createFooService
            ->expects(self::once())
            ->method('execute')
            ->with(FooIdStub::default(), FooStub::DEFAULT_FOO_NAME);
    }

    private function whenTheCommandHandlerIsInvoked(): void
    {
        $sut = new CreateFooCommandHandler($this->createFooService);
        $sut->__invoke($this->command);
    }
}
