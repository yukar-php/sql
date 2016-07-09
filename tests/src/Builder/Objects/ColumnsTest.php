<?php
namespace Yukar\Sql\Tests\Builder\Objects;

use Yukar\Sql\Builder\Operators\Alias;
use Yukar\Sql\Builder\Operators\Between;
use Yukar\Sql\Builder\Operators\Expression;
use Yukar\Sql\Builder\Operators\Order;
use Yukar\Sql\Builder\Objects\Columns;

/**
 * クラス Columns の単体テスト
 *
 * @author hiroki sugawara
 */
class ColumnsTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_COLUMN_LIST = 'column_list';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Columns コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getColumnsInstance(): Columns
    {
        return (new \ReflectionClass('Yukar\Sql\Builder\Objects\Columns'))->newInstanceWithoutConstructor();
    }

    /**
     * 単体テスト対象となるクラスの指定した名前のプロパティのリクレクションインスタンスを取得します。
     *
     * @param Columns $object       単体テスト対象となるクラスのインスタンス
     * @param string $property_name リフレクションを取得するプロパティの名前
     *
     * @return \ReflectionProperty 指定した名前のプロパティのリフレクションを持つインスタンス
     */
    private function getProperty(Columns $object, string $property_name): \ReflectionProperty
    {
        $property = (new \ReflectionClass($object))->getProperty($property_name);
        $property->setAccessible(true);

        return $property;
    }

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
        $this->getProperty($object, self::PROP_NAME_COLUMN_LIST)->setValue($object, $base_list);

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
        $reflector = $this->getProperty($object, self::PROP_NAME_COLUMN_LIST);
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
            [ '\DomainException', [ '1', '2' ], [ new Expression('a', 1), new Between('b', 1, 2) ] ],
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
        $this->getProperty($object, self::PROP_NAME_COLUMN_LIST)->setValue($object, $prop_value);
        $object->setColumns($set_list);
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
            [ 'a AS alias_a, b AS alias_b', [ new Alias('a', 'alias_a'), new Alias('b', 'alias_b') ] ],
            [ 'o ASC, p DESC', [ new Order('o'), new Order('p', Order::DESCENDING) ] ],
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
