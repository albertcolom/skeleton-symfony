<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Command\Delete;

use App\Context\Foo\Application\Command\Delete\DeleteFooService;
use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Context\Foo\Domain\Write\Repository\FooRepository;
use App\Tests\Shared\Context\Foo\Domain\FooIdStub;
use App\Tests\Shared\Context\Foo\Domain\FooStub;
use PHPUnit\Framework\TestCase;

class DeleteFooServiceTest extends TestCase
{
    private FooRepository $fooRepository;
    private FooId|null $fooId;

    protected function setUp(): void
    {
        $this->fooRepository = $this->createMock(FooRepository::class);
        $this->fooId = null;
    }

    public function testItShouldThrowExceptionWhenFooAlreadyExists(): void
    {
        $this->givenDataToDeleteFoo();
        $this->givenNonExistingFoo();
        $this->thenThrowFooNotFoundExceptionException();
        $this->thenFooIsNotDeleted();
        $this->whenExecuteTheService();
    }

    public function testItShouldDeleteWhenWhenFooNotExists(): void
    {
        $this->givenDataToDeleteFoo();
        $this->givenExistingFoo();
        $this->thenFooIsDeleted();
        $this->whenExecuteTheService();
    }

    private function givenDataToDeleteFoo():  void
    {
        $this->fooId = FooIdStub::default();
    }

    private function givenNonExistingFoo(): void
    {
        $this->fooRepository->expects(self::once())->method('findById')->willReturn(null);
    }

    private function givenExistingFoo(): void
    {
        $this->fooRepository->expects(self::once())->method('findById')->willReturn(FooStub::default());
    }

    private function thenThrowFooNotFoundExceptionException(): void
    {
        $this->expectException(FooNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Foo with id %s not found', FooIdStub::DEFAULT_FOO_ID));
    }

    private function thenFooIsNotDeleted(): void
    {
        $this->fooRepository->expects(self::never())->method('remove');
    }

    private function thenFooIsDeleted(): void
    {
        $this->fooRepository->expects(self::once())->method('remove')->with(FooIdStub::default());
    }

    private function whenExecuteTheService(): void
    {
        $sut = new DeleteFooService($this->fooRepository);
        $sut->execute($this->fooId);
    }
}
