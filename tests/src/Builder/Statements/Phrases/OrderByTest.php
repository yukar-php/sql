<?php
namespace Yukar\Sql\Tests\Builder\Statements\Phrases;

use Yukar\Sql\Builder\Operators\Order;
use Yukar\Sql\Builder\Objects\Columns;
use Yukar\Sql\Builder\Statements\Phrases\OrderBy;

/**
 * クラス OrderBy の単体テスト
 *
 * @author hiroki sugawara
 */
class OrderByTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_ORDER_BY_LIST = 'order_by_list';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return object コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getOrderByInstance()
    {
        return (new \ReflectionClass(OrderBy::class))->newInstanceWithoutConstructor();
    }

    /**
     * 単体テスト対象となるクラスの指定した名前のプロパティのリクレクションインスタンスを取得します。
     *
     * @param object $object        単体テスト対象となるクラスのインスタンス
     * @param string $property_name リフレクションを取得するプロパティの名前
     *
     * @return \ReflectionProperty 指定した名前のプロパティのリフレクションを持つインスタンス
     */
    private function getProperty($object, string $property_name): \ReflectionProperty
    {
        $property = (new \ReflectionClass($object))->getProperty($property_name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * 正常系テスト
     */
    public function testGetPhraseString()
    {
        $this->assertSame('ORDER BY %s', $this->getOrderByInstance()->getPhraseString());
    }

    /**
     * メソッド testGetOrderBy のデータプロバイダー
     *
     * @return array
     */
    public function providerGetOrderBy()
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
     * @param array $expected     期待値
     * @param Columns $prop_value プロパティ order_by_list の値
     */
    public function testGetOrderBy($expected, $prop_value)
    {
        $object = $this->getOrderByInstance();
        $this->getProperty($object, self::PROP_NAME_ORDER_BY_LIST)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getOrderBy()->getColumns());
    }

    /**
     * メソッド testSetOrderBy のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOrderBy()
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
     * @param array $expected   期待値
     * @param mixed $prop_value プロパティ order_by_list の値
     * @param Columns $order_by メソッド setOrderBy の引数 order_by に渡す値
     */
    public function testSetOrderBy($expected, $prop_value, $order_by)
    {
        $object = $this->getOrderByInstance();
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
    public function providerSetOrderByFailure()
    {
        return [
            [ '\InvalidArgumentException', null, new Columns() ],
            [ '\InvalidArgumentException', new Columns([ 'a', 'b' ]), new Columns() ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetOrderByFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ order_by_list の値
     * @param Columns $order_by    メソッド setOrderBy の引数 order_by に渡す値
     */
    public function testSetOrderByFailure($expected, $prop_value, $order_by)
    {
        $this->expectException($expected);

        $object = $this->getOrderByInstance();
        $this->getProperty($object, self::PROP_NAME_ORDER_BY_LIST)->setValue($object, $prop_value);
        $object->setOrderBy($order_by);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
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
     * @param string $expected  期待値
     * @param Columns $order_by コンストラクタの引数 order_by に渡す値
     */
    public function testToString($expected, $order_by)
    {
        $this->assertSame($expected, (string)new OrderBy($order_by));
    }
}
