<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Query\Find;

use App\Context\Foo\Application\Query\Find\FindFooQuery;
use App\Context\Foo\Application\Query\Find\FindFooQueryHandler;
use App\Context\Foo\Application\Query\Find\FindFooQueryResponse;
use App\Context\Foo\Application\Query\Find\FindFooService;
use App\Tests\Shared\Context\Foo\Domain\FooIdMother;
use App\Tests\Shared\Context\Foo\Domain\FooMother;
use PHPUnit\Framework\TestCase;

class FindFooQueryHandlerTest extends TestCase
{
    private FindFooService $findFooService;
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
        $this->givenFindFooByIdQuery();
        $this->givenAnExistingFoo();
        $this->whenTheCommandHandlerIsInvoked();
        $this->thenGetExpectedResponseWithBarCollection();
    }

    private function givenFindFooByIdQuery(): void
    {
        $this->query = new FindFooQuery(FooIdMother::DEFAULT_FOO_ID);
    }

    private function givenAnExistingFoo(): void
    {
        $this->findFooService
            ->expects(self::once())
            ->method('execute')
            ->with(FooIdMother::default())
            ->willReturn(FindFooQueryResponse::fromFoo(FooMother::default()));
    }

    private function whenTheCommandHandlerIsInvoked(): void
    {
        $sut = new FindFooQueryHandler($this->findFooService);
        $this->response = $sut->__invoke($this->query);
    }

    private function thenGetExpectedResponseWithBarCollection(): void
    {
        $expected = [
            'id' => FooIdMother::DEFAULT_FOO_ID,
            'name' => FooMother::DEFAULT_FOO_NAME,
            'bar' => [],
        ];

        self::assertSame($expected, $this->response->result());
    }
}
