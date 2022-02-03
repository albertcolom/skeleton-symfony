<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Command\Create;

use App\Context\Foo\Application\Command\Create\CreateFooService;
use App\Context\Foo\Domain\Exception\FooAlreadyExistException;
use App\Context\Foo\Domain\Repository\Write\FooRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Tests\Shared\Context\Foo\Domain\FooIdMother;
use App\Tests\Shared\Context\Foo\Domain\FooMother;
use PHPUnit\Framework\TestCase;

class CreateFooServiceTest extends TestCase
{
    private FooRepository $fooRepository;
    private FooId|null $fooId;
    private string|null $name;

    protected function setUp(): void
    {
        $this->fooRepository = $this->createMock(FooRepository::class);
        $this->fooId = null;
        $this->name = null;
    }

    public function testItShouldThrowExceptionWhenFooAlreadyExists(): void
    {
        $this->givenDataToCreateFoo();
        $this->givenExistingFoo();
        $this->thenThrowFooAlreadyExistException();
        $this->thenFooIsNotCreated();
        $this->whenExecuteTheService();
    }

    public function testItShouldCreateWhenWhenFooNotExists(): void
    {
        $this->givenDataToCreateFoo();
        $this->givenNonExistingFoo();
        $this->thenFooIsCreated();
        $this->whenExecuteTheService();
    }

    private function givenDataToCreateFoo():  void
    {
        $this->fooId = FooIdMother::default();
        $this->name = FooMother::DEFAULT_FOO_NAME;
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

    private function whenExecuteTheService(): void
    {
        $sut = new CreateFooService($this->fooRepository);
        $sut->execute($this->fooId, $this->name);
    }
}
