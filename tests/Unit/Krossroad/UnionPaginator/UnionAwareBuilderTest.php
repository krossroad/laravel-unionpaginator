<?php
namespace Tests\Unit\Krossroad\UnionPaginator\UnionAwareBuilderTest;

use Tests\Unit\TestCase;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Grammars\Grammar;
use Krossroad\UnionPaginator\UnionAwareBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Processors\Processor;

/**
 * Class UnionAwareBuilderTest
 *
 * @package Tests\Unit\Krossroad\UnionPaginator\UnionAwareBuilderTest
 * @covers UnionAwareBuilder
 */
class UnionAwareBuilderTest extends TestCase
{
    /**
     * @covers ::unionPaginate
     */
    public function testUnionPaginateReturnsLengthAwarePaginator()
    {
        $queryBuilderMock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryBuilderMock->expects($this->once())->method('toSql');

        $currentModelMock = $this->getMockBuilder(Model::class)
            ->setMethods(['getPerPage', 'newCollection'])
            ->getMock();

        $currentModelMock->expects($this->once())
            ->method('getPerPage')
            ->willReturn(10);

        $unionAwareBuilderMock = $this->getMockBuilder(UnionAwareBuilder::class)
            ->setMethods([
                'getCurrentModel',
                'getConnection'
            ])
            ->setConstructorArgs([$queryBuilderMock])
            ->getMock();

        $unionAwareBuilderMock
            ->expects($this->any())
            ->method('getCurrentModel')
            ->willReturn($currentModelMock);

        $connectionMock = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getQueryGrammar',
                'getPostProcessor',
                'select'
            ])
            ->getMock();

        $queryGrammarMock = $this->getMockBuilder(Grammar::class)
            ->disableOriginalConstructor()
            ->getMock();

        $connectionMock->expects($this->once())
            ->method('getQueryGrammar')
            ->willReturn($queryGrammarMock);

        $postProcessorMock = $this->getMockBuilder(Processor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $connectionMock->expects($this->once())
            ->method('getPostProcessor')
            ->willReturn($postProcessorMock);

        $unionAwareBuilderMock
            ->expects($this->once())
            ->method('getConnection')
            ->willReturn($connectionMock);

        $this->assertInstanceOf(
            LengthAwarePaginator::class,
            $unionAwareBuilderMock->unionPaginate()
        );
    }
}
