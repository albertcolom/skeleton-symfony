<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Query\FindAll;

use App\Context\Foo\Application\Query\FindAll\FindAllFooQueryResponse;
use App\Context\Foo\Application\Query\FindAll\FindAllFooService;
use App\Context\Foo\Domain\Read\Repository\Read\FooViewRepository;
use App\Context\Foo\Domain\Read\View\FooViewCollection;
use App\Tests\Shared\Context\Foo\Domain\Bar\BarIdStub;
use App\Tests\Shared\Context\Foo\Domain\Bar\BarStub;
use App\Tests\Shared\Stubs\Foo\Read\FooViewCollectionMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FindAllFooServiceTest extends TestCase
{
    private FooViewRepository|MockObject $fooViewRepository;
    private FindAllFooQueryResponse|null $response;

    protected function setUp(): void
    {
        $this->fooViewRepository = $this->createMock(FooViewRepository::class);
        $this->response = null;
    }

    public function testItShouldGetExpectedResultWhenFound(): void
    {
        $fooViewCollection = FooViewCollectionMother::random();

        $this->givenFooViewCollection($fooViewCollection);
        $this->whenExecuteTheService();
        $this->thenGetExpectedResponseWithEmptyBarCollection($fooViewCollection);
    }

    public function testItShouldGetExpectedResultWhenNotFound(): void
    {
        $this->givenEmptyFooCollection();
        $this->whenExecuteTheService();
        $this->thenGetExpectedEmptyResponse();
    }

    private function givenFooViewCollection(FooViewCollection $fooViewCollection): void
    {
        $this->fooViewRepository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn($fooViewCollection);
    }

    private function givenEmptyFooCollection(): void
    {
        $this->fooViewRepository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn(FooViewCollectionMother::create());
    }

    private function thenGetExpectedResponseWithEmptyBarCollection(FooViewCollection $fooViewCollection): void
    {
        $expected = [
            [
                'id' => $fooViewCollection->first()->id,
                'name' => $fooViewCollection->first()->name,
                'bar' => [
                    [
                        'id' => $fooViewCollection->first()->barsView->first()->id,
                        'name' => $fooViewCollection->first()->barsView->first()->name,
                    ],
                    [
                        'id' => $fooViewCollection->first()->barsView->last()->id,
                        'name' => $fooViewCollection->first()->barsView->last()->name,
                    ],
                ],
                'created_at' => $fooViewCollection->first()->createdAt,
            ],
            [
                'id' => $fooViewCollection->last()->id,
                'name' => $fooViewCollection->last()->name,
                'bar' => [
                    [
                        'id' => $fooViewCollection->last()->barsView->first()->id,
                        'name' => $fooViewCollection->last()->barsView->first()->name,
                    ],
                    [
                        'id' => $fooViewCollection->last()->barsView->last()->id,
                        'name' => $fooViewCollection->last()->barsView->last()->name,
                    ],
                ],
                'created_at' => $fooViewCollection->last()->createdAt,
            ],
        ];

        self::assertSame($expected, $this->response->result());
    }

    private function thenGetExpectedEmptyResponse(): void
    {
        self::assertEmpty($this->response->result());
    }

    private function whenExecuteTheService(): void
    {
        $sut = new FindAllFooService($this->fooViewRepository);
        $this->response = $sut->execute();
    }
}
