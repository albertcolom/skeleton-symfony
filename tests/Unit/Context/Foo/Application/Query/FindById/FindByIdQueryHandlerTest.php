<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Query\FindById;

use App\Context\Foo\Application\Query\Find\FindFooQuery;
use App\Context\Foo\Application\Query\Find\FindFooQueryHandler;
use App\Context\Foo\Application\Query\Find\FindFooQueryResponse;
use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Repository\FooRepository;
use App\Tests\Shared\Context\Foo\Domain\Bar\BarMother;
use App\Tests\Shared\Context\Foo\Domain\Bar\BarIdMother;
use App\Tests\Shared\Context\Foo\Domain\FooMother;
use App\Tests\Shared\Context\Foo\Domain\FooIdMother;
use PHPUnit\Framework\TestCase;

class FindByIdQueryHandlerTest extends TestCase
{
    private FooRepository $fooRepository;
    private FindFooQuery|null $query;
    private FindFooQueryResponse|null $response;

    protected function setUp(): void
    {
        $this->fooRepository = $this->createMock(FooRepository::class);
        $this->query = null;
        $this->response = null;
    }

    public function testItShouldThrowExceptionWhenFooNotFound(): void
    {
        $this->givenFindFooByIdQuery();
        $this->givenNonExistingFoo();
        $this->thenThrowFooNotFoundExceptionException();
        $this->whenTheCommandHandlerIsInvoked();
    }

    public function testItShouldGetExpectedResultWhenFooExists(): void
    {
        $this->givenFindFooByIdQuery();
        $this->givenExistingFoo();
        $this->whenTheCommandHandlerIsInvoked();
        $this->thenGetExpectedResponseWithEmptyBarCollection();
    }

    public function testItShouldGetExpectedResultWithBarCollectionWhenFooExists(): void
    {
        $this->givenFindFooByIdQuery();
        $this->givenExistingFooWithBar();
        $this->whenTheCommandHandlerIsInvoked();
        $this->thenGetExpectedResponseWithBarCollection();
    }

    private function givenFindFooByIdQuery(): void
    {
        $this->query = new FindFooQuery(FooIdMother::DEFAULT_FOO_ID);
    }

    private function givenNonExistingFoo(): void
    {
        $this->fooRepository->expects(self::once())->method('findById')->willReturn(null);
    }

    private function givenExistingFoo(): void
    {
        $this->fooRepository->expects(self::once())->method('findById')->willReturn(FooMother::default());
    }

    private function givenExistingFooWithBar(): void
    {
        $foo = FooMother::default();
        $foo->addBar(BarMother::default());
        $foo->addBar(BarMother::default());

        $this->fooRepository->expects(self::once())->method('findById')->willReturn($foo);
    }

    private function thenThrowFooNotFoundExceptionException(): void
    {
        $this->expectException(FooNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Foo with id %s not found', FooIdMother::DEFAULT_FOO_ID));
    }

    private function whenTheCommandHandlerIsInvoked(): void
    {
        $sut = new FindFooQueryHandler($this->fooRepository);
        $this->response = $sut->__invoke($this->query);
    }

    private function thenGetExpectedResponseWithEmptyBarCollection(): void
    {
        $expected = [
            'id' => FooIdMother::DEFAULT_FOO_ID,
            'name' => FooMother::DEFAULT_FOO_NAME,
            'bar' => [],
        ];

        self::assertSame($expected, $this->response->result());
    }

    private function thenGetExpectedResponseWithBarCollection(): void
    {
        $expected = [
            'id' => FooIdMother::DEFAULT_FOO_ID,
            'name' => FooMother::DEFAULT_FOO_NAME,
            'bar' => [
                [
                    'id' => BarIdMother::DEFAULT_BAR_ID,
                    'name' => BarMother::DEFAULT_BAR_NAME,
                ],
                [
                    'id' => BarIdMother::DEFAULT_BAR_ID,
                    'name' => BarMother::DEFAULT_BAR_NAME,
                ],
            ],
        ];

        self::assertSame($expected, $this->response->result());
    }
}
