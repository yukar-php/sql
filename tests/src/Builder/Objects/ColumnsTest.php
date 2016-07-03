<?php
namespace Yukar\Sql\Tests\Builder\Objects;

use Yukar\Sql\Builder\Functions\Order;
use Yukar\Sql\Builder\Objects\Columns;

/**
 * クラス Columns の単体テスト
 *
 * @author hiroki sugawara
 */
class ColumnsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * メソッド testGetColumns のデータプロバイダー
     *
     * @return array
     */
    public function providerGetColumns()
    {
        $order_obj = new Order('x', Order::DESCENDING);

        return [
            [ [ 'a', 'b', 'c' ], [ 'a', 'b', 'c' ] ],
            [ [ $order_obj, 'y' ], [ $order_obj, 'y' ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetColumns
     *
     * @param array $expected  期待値
     * @param array $base_list プロパティ column_list の値
     */
    public function testGetColumns($expected, $base_list)
    {
        $object = $this->getColumnsInstance();
        $this->getColumnListProperty(new \ReflectionClass($object))->setValue($object, $base_list);

        $this->assertSame($expected, $object->getColumns());
    }

    /**
     * メソッド testSetColumns のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumns()
    {
        return [
            [ [ 'a', 'b', 'c' ], [], [ 'a', 'b', 'c' ] ],
            [ [ '1', '2', '3' ], [], [ '1', '2', '3' ] ],
            [ [ 'x', 'y' ], [ 'a', 'b', 'c' ], [ 'x', 'y' ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetColumns
     *
     * @param array $expected   期待値
     * @param array $prop_value プロパティ column_list の値
     * @param array $set_list   メソッド setColumns の引数 columns に渡す値
     */
    public function testSetColumns($expected, $prop_value, $set_list)
    {
        $object = $this->getColumnsInstance();
        $reflector = $this->getColumnListProperty(new \ReflectionClass($object));
        $reflector->setValue($object, $prop_value);
        $object->setColumns($set_list);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetColumnsFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumnsFailure()
    {
        return [
            [ '\InvalidArgumentException', [], [] ],
            [ '\InvalidArgumentException', [ 'a', 'b' ], [] ],
            [ '\DomainException', [], [ 0 ] ],
            [ '\DomainException', [ '1', '2' ], [ 0, 1 ] ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetColumnsFailure
     *
     * @param \Exception $expected 期待値
     * @param array $prop_value    プロパティ column_list の値
     * @param array $set_list      メソッド setColumns の引数 columns に渡す値
     */
    public function testSetColumnsFailure($expected, $prop_value, $set_list)
    {
        $this->expectException($expected);

        $object = $this->getColumnsInstance();
        $this->getColumnListProperty(new \ReflectionClass($object))->setValue($object, $prop_value);
        $object->setColumns($set_list);
    }

    /**
     * コンストラクタを通さずに作成した Columns クラスの新しいインスタンスを取得します。
     *
     * @return Columns コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getColumnsInstance(): Columns
    {
        return (new \ReflectionClass('Yukar\Sql\Builder\Objects\Columns'))->newInstanceWithoutConstructor();
    }

    /**
     * プロパティ column_list のリフレクションを持つインスタンスを取得します。
     *
     * @param \ReflectionClass $reflector クラス Columns のオブジェクトのリフレクション
     *
     * @return \ReflectionProperty プロパティ column_list のリフレクションを持つインスタンス
     */
    private function getColumnListProperty(\ReflectionClass $reflector): \ReflectionProperty
    {
        $property = $reflector->getProperty('column_list');
        $property->setAccessible(true);

        return $property;
    }

    /**
     * testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        return [
            [ "*", [] ],
            [ "a, b, c", [ 'a', 'b', 'c' ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected 期待値
     * @param array $base_list コンストラクタの引数 columns に渡す値
     */
    public function testToString($expected, $base_list)
    {
        $this->assertSame($expected, (string)new Columns($base_list));
    }
}
