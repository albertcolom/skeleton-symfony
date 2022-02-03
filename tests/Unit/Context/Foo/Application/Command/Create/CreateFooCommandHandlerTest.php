<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Command\Create;

use App\Context\Foo\Application\Command\Create\CreateFooCommand;
use App\Context\Foo\Application\Command\Create\CreateFooCommandHandler;
use App\Context\Foo\Domain\Exception\FooAlreadyExistException;
use App\Context\Foo\Domain\Repository\Write\FooRepository;
use App\Tests\Shared\Context\Foo\Domain\FooIdMother;
use App\Tests\Shared\Context\Foo\Domain\FooMother;
use PHPUnit\Framework\TestCase;

class CreateFooCommandHandlerTest extends TestCase
{
    private FooRepository $fooRepository;
    private CreateFooCommand|null $command;

    protected function setUp(): void
    {
        $this->fooRepository = $this->createMock(FooRepository::class);
        $this->command = null;
    }

    public function testItShouldThrowExceptionWhenFooAlreadyExists(): void
    {
        $this->givenCreateFooCommand();
        $this->givenExistingFoo();
        $this->thenThrowFooAlreadyExistException();
        $this->thenFooIsNotCreated();
        $this->whenTheCommandHandlerIsInvoked();
    }

    public function testItShouldCreateWhenWhenFooNotExists(): void
    {
        $this->givenCreateFooCommand();
        $this->givenNonExistingFoo();
        $this->thenFooIsCreated();
        $this->whenTheCommandHandlerIsInvoked();
    }

    private function givenCreateFooCommand(): void
    {
        $this->command = new CreateFooCommand(FooIdMother::DEFAULT_FOO_ID, FooMother::DEFAULT_FOO_NAME);
    }

    private function givenExistingFoo(): void
    {
        $this->fooRepository->expects(self::once())->method('findById')->willReturn(FooMother::default());
    }

    private function givenNonExistingFoo(): void
    {
        $this->fooRepository->expects(self::once())->method('findById')->willReturn(null);
    }

    private function thenThrowFooAlreadyExistException(): void
    {
        $this->expectException(FooAlreadyExistException::class);
        $this->expectExceptionMessage(sprintf('Foo with id %s already exists', FooIdMother::DEFAULT_FOO_ID));
    }

    private function thenFooIsNotCreated(): void
    {
        $this->fooRepository->expects(self::never())->method('save');
    }

    private function thenFooIsCreated(): void
    {
        $this->fooRepository->expects(self::once())->method('save')->with(FooMother::default());
    }

    private function whenTheCommandHandlerIsInvoked(): void
    {
        $sut = new CreateFooCommandHandler($this->fooRepository);
        $sut->__invoke($this->command);
    }
}
