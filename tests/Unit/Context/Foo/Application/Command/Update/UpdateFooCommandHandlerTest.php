<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Command\Update;

use App\Context\Foo\Application\Command\Update\UpdateFooCommand;
use App\Context\Foo\Application\Command\Update\UpdateFooCommandHandler;
use App\Context\Foo\Application\Command\Update\UpdateFooService;
use App\Tests\Shared\Context\Foo\Domain\FooIdStub;
use App\Tests\Shared\Context\Foo\Domain\FooStub;
use PHPUnit\Framework\TestCase;

class UpdateFooCommandHandlerTest extends TestCase
{
    private UpdateFooService $updateFooService;
    private UpdateFooCommand|null $command;

    protected function setUp(): void
    {
        $this->updateFooService = $this->createMock(UpdateFooService::class);
        $this->command = null;
    }

    public function testItShouldThrowExceptionWhenFooAlreadyExists(): void
    {
        $this->givenUpdateFooCommand();
        $this->thenFooIsUpdated();
        $this->whenTheCommandHandlerIsInvoked();
    }

    private function givenUpdateFooCommand(): void
    {
        $this->command = new UpdateFooCommand(FooIdStub::DEFAULT_FOO_ID, FooStub::DEFAULT_FOO_NAME);
    }

    private function thenFooIsUpdated(): void
    {
        $this->updateFooService
            ->expects(self::once())
            ->method('execute')
            ->with(FooIdStub::default());
    }

    private function whenTheCommandHandlerIsInvoked(): void
    {
        $sut = new UpdateFooCommandHandler($this->updateFooService);
        $sut->__invoke($this->command);
    }
}
