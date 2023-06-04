<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Query\Find;

use App\Context\Foo\Application\Query\Find\FindFooService;
use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Read\Repository\FooViewRepository;
use App\Context\Foo\Domain\Read\View\FooView;
use App\Context\Foo\Domain\Write\ValueObject\FooId;
use App\Tests\Shared\Context\Foo\Domain\FooIdStub;
use App\Tests\Shared\Stubs\Foo\Read\FooViewMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FindFooServiceTest extends TestCase
{
    private FooViewRepository|MockObject $fooViewRepository;
    private FooId|null $fooId;
    private FooView|null $response;

    protected function setUp(): void
    {
        $this->fooViewRepository = $this->createMock(FooViewRepository::class);
        $this->fooId = null;
        $this->response = null;
    }

    public function testItShouldThrowExceptionWhenFooNotFound(): void
    {
        $this->givenDataToFindFoo();
        $this->givenNonExistingFoo();
        $this->thenThrowFooNotFoundExceptionException();
        $this->whenExecuteTheService();
    }

    public function testItShouldGetExpectedResultWhenFooExists(): void
    {
        $fooView = FooViewMother::randomWithoutBars();

        $this->givenDataToFindFoo();
        $this->givenExistingFooView($fooView);
        $this->whenExecuteTheService();
        $this->thenGetExpectedResponseWithEmptyBarCollection($fooView);
    }

    public function testItShouldGetExpectedResultWithBarCollectionWhenFooExists(): void
    {
        $fooView = FooViewMother::random();

        $this->givenDataToFindFoo();
        $this->givenExistingFooView($fooView);
        $this->whenExecuteTheService();
        $this->thenGetExpectedResponseWithBarCollection($fooView);
    }

    private function givenDataToFindFoo(): void
    {
        $this->fooId = FooIdStub::default();
    }

    private function givenNonExistingFoo(): void
    {
        $this->fooViewRepository
            ->expects(self::once())
            ->method('findById')
            ->willThrowException(FooNotFoundException::fromFooId($this->fooId->value));
    }

    private function givenExistingFooView(FooView $fooView): void
    {
        $this->fooViewRepository->expects(self::once())->method('findById')->willReturn($fooView);
    }

    private function thenThrowFooNotFoundExceptionException(): void
    {
        $this->expectException(FooNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Foo with id %s not found', FooIdStub::DEFAULT_FOO_ID));
    }

    private function whenExecuteTheService(): void
    {
        $sut = new FindFooService($this->fooViewRepository);
        $this->response = $sut->execute($this->fooId);
    }

    private function thenGetExpectedResponseWithEmptyBarCollection(FooView $fooView): void
    {
        $expected = [
            'id' => $fooView->id,
            'name' => $fooView->name,
            'bar' => [],
            'created_at' => $fooView->createdAt,
        ];

        self::assertSame($expected, $this->response->toArray());
    }

    private function thenGetExpectedResponseWithBarCollection(FooView $fooView): void
    {
        $expected = [
            'id' => $fooView->id,
            'name' => $fooView->name,
            'bar' => [
                [
                    'id' => $fooView->barsView->first()->id,
                    'name' => $fooView->barsView->first()->name,
                ],
                [
                    'id' => $fooView->barsView->last()->id,
                    'name' => $fooView->barsView->last()->name,
                ],
            ],
            'created_at' => $fooView->createdAt,
        ];

        self::assertSame($expected, $this->response->toArray());
    }
}
