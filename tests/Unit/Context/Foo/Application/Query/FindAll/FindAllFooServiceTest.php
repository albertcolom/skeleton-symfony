<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Foo\Application\Query\FindAll;

use App\Context\Foo\Application\Query\FindAll\FindAllFooQueryResponse;
use App\Context\Foo\Application\Query\FindAll\FindAllFooService;
use App\Context\Foo\Domain\FooCollection;
use App\Context\Foo\Domain\Repository\Read\FooViewRepository;
use App\Tests\Shared\Context\Foo\Domain\Bar\BarIdStub;
use App\Tests\Shared\Context\Foo\Domain\Bar\BarStub;
use App\Tests\Shared\Context\Foo\Domain\FooIdStub;
use App\Tests\Shared\Context\Foo\Domain\FooStub;
use PHPUnit\Framework\TestCase;

class FindAllFooServiceTest extends TestCase
{
    private FooViewRepository $fooViewRepository;
    private FindAllFooQueryResponse|null $response;

    protected function setUp(): void
    {
        $this->fooViewRepository = $this->createMock(FooViewRepository::class);
        $this->response = null;
    }

    public function testItShouldGetExpectedResultWhenFound(): void
    {
        $this->givenFooCollection();
        $this->whenExecuteTheService();
        $this->thenGetExpectedResponseWithEmptyBarCollection();
    }

    public function testItShouldGetExpectedResultWhenNotFound(): void
    {
        $this->givenEmptyFooCollection();
        $this->whenExecuteTheService();
        $this->thenGetExpectedEmptyResponse();
    }

    private function givenFooCollection(): void
    {
        $foo = FooStub::default();
        $foo->addBar(BarStub::default());

        $this->fooViewRepository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn(new FooCollection([$foo, FooStub::default()]));
    }

    private function givenEmptyFooCollection(): void
    {
        $this->fooViewRepository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn(FooCollection::createEmpty());
    }

    private function thenGetExpectedResponseWithEmptyBarCollection(): void
    {
        $expected = [
            [
                'id' => FooIdStub::DEFAULT_FOO_ID,
                'name' => FooStub::DEFAULT_FOO_NAME,
                'created_at' => FooStub::DEFAULT_CREATED_AT,
                'bar' => [
                    [
                        'id' => BarIdStub::DEFAULT_BAR_ID,
                        'name' => BarStub::DEFAULT_BAR_NAME,
                    ]
                ],
            ],
            [
                'id' => FooIdStub::DEFAULT_FOO_ID,
                'name' => FooStub::DEFAULT_FOO_NAME,
                'created_at' => FooStub::DEFAULT_CREATED_AT,
                'bar' => [],
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
