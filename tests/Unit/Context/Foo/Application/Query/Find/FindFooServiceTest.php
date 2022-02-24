<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Query\Find;

use App\Context\Foo\Application\Query\Find\FindFooQueryResponse;
use App\Context\Foo\Application\Query\Find\FindFooService;
use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Repository\Read\FooViewRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Tests\Shared\Context\Foo\Domain\Bar\BarIdStub;
use App\Tests\Shared\Context\Foo\Domain\Bar\BarStub;
use App\Tests\Shared\Context\Foo\Domain\FooIdStub;
use App\Tests\Shared\Context\Foo\Domain\FooStub;
use PHPUnit\Framework\TestCase;

class FindFooServiceTest extends TestCase
{
    private FooViewRepository $fooViewRepository;
    private FooId|null $fooId;
    private FindFooQueryResponse|null $response;

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
        $this->givenDataToFindFoo();
        $this->givenExistingFoo();
        $this->whenExecuteTheService();
        $this->thenGetExpectedResponseWithEmptyBarCollection();
    }

    public function testItShouldGetExpectedResultWithBarCollectionWhenFooExists(): void
    {
        $this->givenDataToFindFoo();
        $this->givenExistingFooWithBar();
        $this->whenExecuteTheService();
        $this->thenGetExpectedResponseWithBarCollection();
    }

    private function givenDataToFindFoo(): void
    {
        $this->fooId = FooIdStub::default();
    }

    private function givenNonExistingFoo(): void
    {
        $this->fooViewRepository->expects(self::once())->method('findById')->willReturn(null);
    }

    private function givenExistingFoo(): void
    {
        $this->fooViewRepository->expects(self::once())->method('findById')->willReturn(FooStub::default());
    }

    private function givenExistingFooWithBar(): void
    {
        $foo = FooStub::default();
        $foo->addBar(BarStub::default());
        $foo->addBar(BarStub::default());

        $this->fooViewRepository->expects(self::once())->method('findById')->willReturn($foo);
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

    private function thenGetExpectedResponseWithEmptyBarCollection(): void
    {
        $expected = [
            'id' => FooIdStub::DEFAULT_FOO_ID,
            'name' => FooStub::DEFAULT_FOO_NAME,
            'created_at' => FooStub::DEFAULT_CREATED_AT,
            'bar' => [],
        ];

        self::assertSame($expected, $this->response->result());
    }

    private function thenGetExpectedResponseWithBarCollection(): void
    {
        $expected = [
            'id' => FooIdStub::DEFAULT_FOO_ID,
            'name' => FooStub::DEFAULT_FOO_NAME,
            'created_at' => FooStub::DEFAULT_CREATED_AT,
            'bar' => [
                [
                    'id' => BarIdStub::DEFAULT_BAR_ID,
                    'name' => BarStub::DEFAULT_BAR_NAME,
                ],
                [
                    'id' => BarIdStub::DEFAULT_BAR_ID,
                    'name' => BarStub::DEFAULT_BAR_NAME,
                ],
            ],
        ];

        self::assertSame($expected, $this->response->result());
    }
}
