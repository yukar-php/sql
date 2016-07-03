<?php
namespace Yukar\Sql\Tests\Builder\Statements\Phrases;

use Yukar\Sql\Builder\Functions\Order;
use Yukar\Sql\Builder\Objects\Columns;
use Yukar\Sql\Builder\Statements\Phrases\OrderBy;

/**
 * クラス OrderBy の単体テスト
 *
 * @author hiroki sugawara
 */
class OrderByTest extends \PHPUnit_Framework_TestCase
{
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
        $this->getOrderByListProperty(new \ReflectionClass($object))->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getOrderBy()->getColumns());
    }

    /**
     * メソッド testGetOrderByFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerGetOrderByFailure()
    {
        return [
            [ '\BadMethodCallException', null ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerGetOrderByFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ order_by_list の値
     */
    public function testGetOrderByFailure($expected, $prop_value)
    {
        $this->expectException($expected);

        $object = $this->getOrderByInstance();
        $this->getOrderByListProperty(new \ReflectionClass($object))->setValue($object, $prop_value);
        $object->getOrderBy();
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
        $reflector = $this->getOrderByListProperty(new \ReflectionClass($object));
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
        $this->getOrderByListProperty(new \ReflectionClass($object))->setValue($object, $prop_value);
        $object->setOrderBy($order_by);
    }

    /**
     * コンストラクタを通さずに作成した OrderBy クラスの新しいインスタンスを取得します。
     *
     * @return OrderBy コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getOrderByInstance(): OrderBy
    {
        return (new \ReflectionClass('Yukar\Sql\Builder\Statements\Phrases\OrderBy'))->newInstanceWithoutConstructor();
    }

    /**
     * プロパティ order_by_list のリフレクションを持つインスタンスを取得します。
     *
     * @param \ReflectionClass $reflector クラス OrderBy のオブジェクトのリフレクション
     *
     * @return \ReflectionProperty プロパティ order_by_list のリフレクションを持つインスタンス
     */
    private function getOrderByListProperty(\ReflectionClass $reflector): \ReflectionProperty
    {
        $property = $reflector->getProperty('order_by_list');
        $property->setAccessible(true);

        return $property;
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
