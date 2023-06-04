<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Query\FindAll;

use App\Context\Foo\Application\Query\FindAll\FindAllFooQuery;
use App\Context\Foo\Application\Query\FindAll\FindAllFooQueryHandler;
use App\Context\Foo\Application\Query\FindAll\FindAllFooQueryResponse;
use App\Context\Foo\Application\Query\FindAll\FindAllFooService;
use App\Context\Foo\Domain\FooCollection;
use App\Tests\Shared\Stubs\Foo\Read\FooViewCollectionMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FindAllFooQueryHandlerTest extends TestCase
{
    private FindAllFooService|MockObject $findAllFooService;
    private FindAllFooQuery $query;
    private FindAllFooQueryResponse|null $response;

    protected function setUp(): void
    {
        $this->findAllFooService = $this->createMock(FindAllFooService::class);
        $this->query = new FindAllFooQuery([]);
        $this->response = null;
    }

    public function testItShouldGetExpectedResult(): void
    {
        $this->givenAnExistingFoo();
        $this->whenTheCommandHandlerIsInvoked();
        $this->thenGetExpectedResponseWithBarCollection();
    }

    private function givenAnExistingFoo(): void
    {
        $this->findAllFooService
            ->expects(self::once())
            ->method('execute')
            ->willReturn(FooViewCollectionMother::create());
    }

    private function whenTheCommandHandlerIsInvoked(): void
    {
        $sut = new FindAllFooQueryHandler($this->findAllFooService);
        $this->response = $sut->__invoke($this->query);
    }

    private function thenGetExpectedResponseWithBarCollection(): void
    {
        self::assertEmpty($this->response->result());
    }
}
