<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Command\Delete;

use App\Context\Foo\Application\Command\Delete\DeleteFooCommand;
use App\Context\Foo\Application\Command\Delete\DeleteFooCommandHandler;
use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Repository\Write\FooRepository;
use App\Tests\Shared\Context\Foo\Domain\FooIdMother;
use App\Tests\Shared\Context\Foo\Domain\FooMother;
use PHPUnit\Framework\TestCase;

class DeleteFooCommandHandlerTest extends TestCase
{
    private FooRepository $fooRepository;
    private DeleteFooCommand|null $command;

    protected function setUp(): void
    {
        $this->fooRepository = $this->createMock(FooRepository::class);
        $this->command = null;
    }

    public function testItShouldThrowExceptionWhenFooAlreadyExists(): void
    {
        $this->givenDeleteFooCommand();
        $this->givenNonExistingFoo();
        $this->thenThrowFooNotFoundExceptionException();
        $this->thenFooIsNotDeleted();
        $this->whenTheCommandHandlerIsInvoked();
    }

    public function testItShouldDeleteWhenWhenFooNotExists(): void
    {
        $this->givenDeleteFooCommand();
        $this->givenExistingFoo();
        $this->thenFooIsDeleted();
        $this->whenTheCommandHandlerIsInvoked();
    }

    private function givenDeleteFooCommand(): void
    {
        $this->command = new DeleteFooCommand(FooIdMother::DEFAULT_FOO_ID);
    }

    private function givenNonExistingFoo(): void
    {
        $this->fooRepository->expects(self::once())->method('findById')->willReturn(null);
    }

    private function givenExistingFoo(): void
    {
        $this->fooRepository->expects(self::once())->method('findById')->willReturn(FooMother::default());
    }

    private function thenThrowFooNotFoundExceptionException(): void
    {
        $this->expectException(FooNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Foo with id %s not found', FooIdMother::DEFAULT_FOO_ID));
    }

    private function thenFooIsNotDeleted(): void
    {
        $this->fooRepository->expects(self::never())->method('remove');
    }

    private function thenFooIsDeleted(): void
    {
        $this->fooRepository->expects(self::once())->method('remove')->with(FooIdMother::default());
    }

    private function whenTheCommandHandlerIsInvoked(): void
    {
        $sut = new DeleteFooCommandHandler($this->fooRepository);
        $sut->__invoke($this->command);
    }
}
