<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Command\Update;

use App\Context\Foo\Application\Command\Update\UpdateFooService;
use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Repository\Write\FooRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Tests\Shared\Context\Foo\Domain\FooIdMother;
use App\Tests\Shared\Context\Foo\Domain\FooMother;
use PHPUnit\Framework\TestCase;

class UpdateFooServiceTest extends TestCase
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
        $this->givenDataToUpdateFoo();
        $this->givenNonExistingFoo();
        $this->thenThrowFooNotFoundExceptionException();
        $this->thenFooIsNotUpdated();
        $this->whenExecuteTheService();
    }

    public function testItShouldDeleteWhenWhenFooNotExists(): void
    {
        $this->givenDataToUpdateFoo();
        $this->givenExistingFoo();
        $this->thenFooIsUpdated();
        $this->whenExecuteTheService();
    }

    private function givenDataToUpdateFoo():  void
    {
        $this->fooId = FooIdMother::default();
        $this->name = FooMother::DEFAULT_FOO_NAME;
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

    private function thenFooIsNotUpdated(): void
    {
        $this->fooRepository->expects(self::never())->method('save');
    }

    private function thenFooIsUpdated(): void
    {
        $this->fooRepository
            ->expects(self::once())
            ->method('save')
            ->with(FooMother::default()->update(FooMother::DEFAULT_FOO_NAME));
    }

    private function whenExecuteTheService(): void
    {
        $sut = new UpdateFooService($this->fooRepository);
        $sut->execute($this->fooId, $this->name);
    }
}
