<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Command\Create;

use App\Context\Foo\Application\Command\Create\CreateFooCommand;
use App\Context\Foo\Application\Command\Create\CreateFooCommandHandler;
use App\Context\Foo\Application\Command\Create\CreateFooService;
use App\Tests\Shared\Context\Foo\Domain\FooIdMother;
use App\Tests\Shared\Context\Foo\Domain\FooMother;
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
        $this->command = new CreateFooCommand(FooIdMother::DEFAULT_FOO_ID, FooMother::DEFAULT_FOO_NAME);
    }

    private function thenFooIsCreated(): void
    {
        $this->createFooService
            ->expects(self::once())
            ->method('execute')
            ->with(FooIdMother::default(), FooMother::DEFAULT_FOO_NAME);
    }

    private function whenTheCommandHandlerIsInvoked(): void
    {
        $sut = new CreateFooCommandHandler($this->createFooService);
        $sut->__invoke($this->command);
    }
}
