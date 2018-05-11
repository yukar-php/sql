<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Phrases;

use Yukar\Sql\Builder\Common\Objects\Columns;
use Yukar\Sql\Builder\Common\Operators\Alias;
use Yukar\Sql\Builder\Common\Operators\Order;
use Yukar\Sql\Builder\Common\Statements\Phrases\OrderBy;
use Yukar\Sql\Interfaces\Builder\Common\Objects\IColumns;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス OrderBy の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Statements\Phrases
 * @author hiroki sugawara
 */
class OrderByTest extends CustomizedTestCase
{
    private const PROP_NAME_ORDER_BY_LIST = 'order_by_list';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return OrderBy::class;
    }

    /**
     * 正常系テスト
     */
    public function testGetPhraseString(): void
    {
        $this->assertSame('ORDER BY %s', $this->getNewInstance()->getPhraseString());
    }

    /**
     * メソッド testGetOrderBy のデータプロバイダー
     *
     * @return array
     */
    public function providerGetOrderBy(): array
    {
        return [
            [ [ 'a', 'b', 'c' ], new Columns([ 'a', 'b', 'c' ]) ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetOrderBy
     *
     * @param array    $expected   期待値
     * @param IColumns $prop_value プロパティ order_by_list の値
     */
    public function testGetOrderBy($expected, $prop_value): void
    {
        /** @var OrderBy $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_ORDER_BY_LIST)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getOrderBy()->getColumns());
    }

    /**
     * メソッド testSetOrderBy のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOrderBy(): array
    {
        return [
            [ [ 'a', 'b', 'c' ], null, new Columns([ 'a', 'b', 'c' ]) ],
            [ [ 'x', 'y', 'z' ], new Columns([ 'a', 'b', 'c' ]), new Columns([ 'x', 'y', 'z' ]) ]
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetOrderBy
     *
     * @param array     $expected   期待値
     * @param mixed     $prop_value プロパティ order_by_list の値
     * @param IColumns $order_by    メソッド setOrderBy の引数 order_by に渡す値
     */
    public function testSetOrderBy($expected, $prop_value, $order_by): void
    {
        /** @var OrderBy $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_ORDER_BY_LIST);
        $reflector->setValue($object, $prop_value);
        $object->setOrderBy($order_by);

        $this->assertSame($expected, $reflector->getValue($object)->getColumns());
    }

    /**
     * メソッド testSetOrderByFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOrderByFailure(): array
    {
        $valid_columns = new Columns([ 'x', 'y', 'z' ]);
        $empty_columns = new Columns();
        $alias_columns = new Columns([ new Alias('orig_table', 'as_table') ]);

        return [
            [ \InvalidArgumentException::class, null, $empty_columns ],
            [ \InvalidArgumentException::class, null, $alias_columns ],
            [ \InvalidArgumentException::class, $valid_columns, $empty_columns ],
            [ \InvalidArgumentException::class, $valid_columns, $alias_columns ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetOrderByFailure
     *
     * @param \Exception $expected   期待値
     * @param mixed      $prop_value プロパティ order_by_list の値
     * @param IColumns   $order_by   メソッド setOrderBy の引数 order_by に渡す値
     */
    public function testSetOrderByFailure($expected, $prop_value, $order_by): void
    {
        $this->expectException($expected);

        /** @var OrderBy $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_ORDER_BY_LIST)->setValue($object, $prop_value);
        $object->setOrderBy($order_by);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        return [
            [ 'ORDER BY a', new Columns([ 'a' ]) ],
            [ 'ORDER BY b ASC', new Columns([ new Order('b') ]) ],
            [ 'ORDER BY c DESC', new Columns([ new Order('c', Order::DESCENDING) ]) ],
            [
                'ORDER BY a ASC, b DESC',
                new Columns([ new Order('a'), new Order('b', Order::DESCENDING) ])
            ],
            [
                'ORDER BY a, b DESC',
                new Columns([ 'a', new Order('b', Order::DESCENDING) ])
            ],
            [
                'ORDER BY x DESC, y ASC',
                new Columns([ new Order('x', Order::DESCENDING), new Order('y') ])
            ],
            [
                'ORDER BY x DESC, y',
                new Columns([ new Order('x', Order::DESCENDING), 'y' ])
            ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string   $expected 期待値
     * @param IColumns $order_by コンストラクタの引数 order_by に渡す値
     */
    public function testToString($expected, $order_by): void
    {
        $this->assertSame($expected, (string)new OrderBy($order_by));
    }
}
