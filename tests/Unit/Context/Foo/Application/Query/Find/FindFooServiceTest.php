<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Query\Find;

use App\Context\Foo\Application\Query\Find\FindFooQueryResponse;
use App\Context\Foo\Application\Query\Find\FindFooService;
use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Repository\Read\FooViewRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Tests\Shared\Context\Foo\Domain\Bar\BarIdMother;
use App\Tests\Shared\Context\Foo\Domain\Bar\BarMother;
use App\Tests\Shared\Context\Foo\Domain\FooIdMother;
use App\Tests\Shared\Context\Foo\Domain\FooMother;
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
        $this->fooId = FooIdMother::default();
    }

    private function givenNonExistingFoo(): void
    {
        $this->fooViewRepository->expects(self::once())->method('findById')->willReturn(null);
    }

    private function givenExistingFoo(): void
    {
        $this->fooViewRepository->expects(self::once())->method('findById')->willReturn(FooMother::default());
    }

    private function givenExistingFooWithBar(): void
    {
        $foo = FooMother::default();
        $foo->addBar(BarMother::default());
        $foo->addBar(BarMother::default());

        $this->fooViewRepository->expects(self::once())->method('findById')->willReturn($foo);
    }

    private function thenThrowFooNotFoundExceptionException(): void
    {
        $this->expectException(FooNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Foo with id %s not found', FooIdMother::DEFAULT_FOO_ID));
    }

    private function whenExecuteTheService(): void
    {
        $sut = new FindFooService($this->fooViewRepository);
        $this->response = $sut->execute($this->fooId);
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
