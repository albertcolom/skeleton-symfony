<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Command\Delete;

use App\Context\Foo\Application\Command\Delete\DeleteFooCommand;
use App\Context\Foo\Application\Command\Delete\DeleteFooCommandHandler;
use App\Context\Foo\Application\Command\Delete\DeleteFooService;
use App\Tests\Shared\Context\Foo\Domain\FooIdMother;
use PHPUnit\Framework\TestCase;

class DeleteFooCommandHandlerTest extends TestCase
{
    private DeleteFooService $deleteFooService;
    private DeleteFooCommand|null $command;

    protected function setUp(): void
    {
        $this->deleteFooService = $this->createMock(DeleteFooService::class);
        $this->command = null;
    }

    public function testItShouldThrowExceptionWhenFooAlreadyExists(): void
    {
        $this->givenDeleteFooCommand();
        $this->thenFooIsDeleted();
        $this->whenTheCommandHandlerIsInvoked();
    }

    private function givenDeleteFooCommand(): void
    {
        $this->command = new DeleteFooCommand(FooIdMother::DEFAULT_FOO_ID);
    }

    private function thenFooIsDeleted(): void
    {
        $this->deleteFooService
            ->expects(self::once())
            ->method('execute')
            ->with(FooIdMother::default());
    }

    private function whenTheCommandHandlerIsInvoked(): void
    {
        $sut = new DeleteFooCommandHandler($this->deleteFooService);
        $sut->__invoke($this->command);
    }
}
