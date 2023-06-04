<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Query\Find;

use App\Context\Foo\Application\Query\Find\FindFooQuery;
use App\Context\Foo\Application\Query\Find\FindFooQueryHandler;
use App\Context\Foo\Application\Query\Find\FindFooQueryResponse;
use App\Context\Foo\Application\Query\Find\FindFooService;
use App\Context\Foo\Domain\Read\View\FooView;
use App\Tests\Shared\Context\Foo\Domain\FooIdStub;
use App\Tests\Shared\Stubs\Foo\Read\FooViewMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FindFooQueryHandlerTest extends TestCase
{
    private FindFooService|MockObject $findFooService;
    private FindFooQuery|null $query;
    private FindFooQueryResponse|null $response;

    protected function setUp(): void
    {
        $this->findFooService = $this->createMock(FindFooService::class);
        $this->query = null;
        $this->response = null;
    }

    public function testItShouldGetExpectedResult(): void
    {
        $fooView = FooViewMother::randomWithoutBars();

        $this->givenFindFooByIdQuery();
        $this->givenAnExistingFoo($fooView);
        $this->whenTheCommandHandlerIsInvoked();
        $this->thenGetExpectedResponseWithBarCollection($fooView);
    }

    private function givenFindFooByIdQuery(): void
    {
        $this->query = new FindFooQuery(FooIdStub::DEFAULT_FOO_ID);
    }

    private function givenAnExistingFoo(FooView $fooView): void
    {
        $this->findFooService
            ->expects(self::once())
            ->method('execute')
            ->with(FooIdStub::default())
            ->willReturn($fooView);
    }

    private function whenTheCommandHandlerIsInvoked(): void
    {
        $sut = new FindFooQueryHandler($this->findFooService);
        $this->response = $sut->__invoke($this->query);
    }

    private function thenGetExpectedResponseWithBarCollection(FooView $fooView): void
    {
        $expected = [
            'id' => $fooView->id,
            'name' => $fooView->name,
            'bar' => [],
            'created_at' => $fooView->createdAt,
        ];

        self::assertSame($expected, $this->response->result());
    }
}
