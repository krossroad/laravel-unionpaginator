<?php
namespace Tests\Unit\Krossroad\UnionPaginator\UnionAwareBuilderTest;

use PHPUnit\Framework\TestCase;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Krossroad\UnionPaginator\UnionAwareBuilder;
use Illuminate\Pagination\LengthAwarePaginator;

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

        $queryBuilderMock->expects($this->any())->method('toSql');

        $currentModelMock = $this->getMockBuilder(Model::class)
            ->setMethods(['getPerPage', 'newCollection'])
            ->getMock();

        $currentModelMock->expects($this->once())
            ->method('getPerPage')
            ->willReturn(10);

        $currentModelMock->expects($this->any())
            ->method('newCollection')
            ->willReturn(collect([]));

        $unionAwareBuilderMock = $this->getMockBuilder(UnionAwareBuilder::class)
            ->setMethods([
                'getCurrentModel',
                'getConnection',
                'getCountForUnionPagination',
                'getCustomQueryBuilder'
            ])
            ->setConstructorArgs([$queryBuilderMock])
            ->getMock();

        $unionAwareBuilderMock
            ->expects($this->any())
            ->method('getCurrentModel')
            ->willReturn($currentModelMock);

        $unionAwareBuilderMock
            ->expects($this->any())
            ->method('getCountForUnionPagination')
            ->willReturn(0);

        $connectionMock = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'select'
            ])
            ->getMock();

        $customerQueryBuilderMock = $this->getMockBuilder(Builder::class)
            ->setConstructorArgs([
                $connectionMock,
                new \Illuminate\Database\Query\Grammars\Grammar,
                new \Illuminate\Database\Query\Processors\Processor
            ])
            ->setMethods(['select'])
            ->getMock();

        $customerQueryBuilderMock->expects($this->any())
            ->method('select')
            ->willReturnSelf();

        $unionAwareBuilderMock
            ->expects($this->any())
            ->method('getCustomQueryBuilder')
            ->with($connectionMock)
            ->willReturn($customerQueryBuilderMock);

        $unionAwareBuilderMock
            ->expects($this->any())
            ->method('getConnection')
            ->willReturn($connectionMock);

        $this->assertInstanceOf(
            LengthAwarePaginator::class,
            $unionAwareBuilderMock->unionPaginate()
        );
    }
}
