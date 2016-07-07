<?php
namespace Yukar\Sql\Tests\Builder\Operators;

use Yukar\Sql\Builder\Operators\Order;

/**
 * クラス Order の単体テスト
 *
 * @author hiroki sugawara
 */
class OrderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 正常系テスト
     */
    public function testGetOperatorFormat()
    {
        $this->assertSame('%s %s', $this->getOrderInstance()->getOperatorFormat());
    }

    /**
     * メソッド testGetColumnName のデータプロバイダー
     *
     * @return array
     */
    public function providerGetColumnName()
    {
        return [
            [ 'TableName', 'TableName' ],
            [ 'schema.TableName', 'schema.TableName' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetColumnName
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ column_name の値
     */
    public function testGetColumnName($expected, $prop_value)
    {
        $object = $this->getOrderInstance();
        $this->getColumnNameProperty(new \ReflectionClass($object))->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getColumnName());
    }

    /**
     * メソッド testSetColumnName のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumnName()
    {
        return [
            [ 'TableName', null, 'TableName' ],
            [ 'TableName', 'BaseName', 'TableName' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetColumnName
     *
     * @param string $expected    期待値
     * @param mixed $prop_value   プロパティ column_name の値
     * @param string $column_name メソッド setColumnName の引数 column_name に渡す値
     */
    public function testSetColumnName($expected, $prop_value, $column_name)
    {
        $object = $this->getOrderInstance();
        $reflector = $this->getColumnNameProperty(new \ReflectionClass($object));
        $reflector->setValue($object, $prop_value);
        $object->setColumnName($column_name);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetColumnNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumnNameFailure()
    {
        return [
            [ '\InvalidArgumentException', null, '' ],
            [ '\InvalidArgumentException', 'TableName', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetColumnNameFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ column_name の値
     * @param mixed $column_name   メソッド setColumnName の引数 column_name に渡す値
     */
    public function testSetColumnNameFailure($expected, $prop_value, $column_name)
    {
        $this->expectException($expected);

        $object = $this->getOrderInstance();
        $this->getColumnNameProperty(new \ReflectionClass($object))->setValue($object, $prop_value);
        $object->setColumnName($column_name);
    }

    /**
     * メソッド testGetOrderType のデータプロバイダー
     *
     * @return array
     */
    public function providerGetOrderType()
    {
        return [
            [ 'ASC', null ],
            [ 'ASC', Order::SORTS[Order::ASCENDING] ],
            [ 'ASC', Order::SORTS[1] ],
            [ 'DESC', Order::SORTS[Order::DESCENDING] ],
            [ 'DESC', Order::SORTS[2] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetOrderType
     *
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ order_type の値
     */
    public function testGetOrderType($expected, $prop_value)
    {
        $object = $this->getOrderInstance();
        $this->getOrderTypeProperty(new \ReflectionClass($object))->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getOrderType());
    }

    /**
     * メソッド testSetOrderType のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOrderType()
    {
        return [
            [ 'ASC', null, 1 ],
            [ 'ASC', 'DESC', 1 ],
            [ 'ASC', null, Order::ASCENDING ],
            [ 'ASC', 'DESC', Order::ASCENDING ],
            [ 'DESC', null, 2 ],
            [ 'DESC', 'ASC', 2 ],
            [ 'DESC', null, Order::DESCENDING ],
            [ 'DESC', 'ASC', Order::DESCENDING ],
            [ 'ASC', null, -1 ],
            [ 'ASC', null, 0 ],
            [ 'ASC', null, 3 ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetOrderType
     *
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ order_type の値
     * @param int $order_type   メソッド setOrderType の引数 order_type に渡す値
     */
    public function testSetOrderType($expected, $prop_value, $order_type)
    {
        $object = $this->getOrderInstance();
        $reflector = $this->getOrderTypeProperty(new \ReflectionClass($object));
        $reflector->setValue($object, $prop_value);

        $this->assertInstanceOf(get_class($object), $object->setOrderType($order_type));
        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * コンストラクタを通さずに作成した Order クラスの新しいインスタンスを取得します。
     *
     * @return Order コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getOrderInstance(): Order
    {
        return (new \ReflectionClass('Yukar\Sql\Builder\Operators\Order'))->newInstanceWithoutConstructor();
    }

    /**
     * プロパティ column_name のリフレクションを持つインスタンスを取得します。
     *
     * @param \ReflectionClass $reflector クラス Order のオブジェクトのリフレクション
     *
     * @return \ReflectionProperty プロパティ column_name のリフレクションを持つインスタンス
     */
    private function getColumnNameProperty(\ReflectionClass $reflector): \ReflectionProperty
    {
        $property = $reflector->getProperty('column_name');
        $property->setAccessible(true);

        return $property;
    }

    /**
     * プロパティ order_type のリフレクションを持つインスタンスを取得します。
     *
     * @param \ReflectionClass $reflector クラス Order のオブジェクトのリフレクション
     *
     * @return \ReflectionProperty プロパティ order_type のリフレクションを持つインスタンス
     */
    private function getOrderTypeProperty(\ReflectionClass $reflector): \ReflectionProperty
    {
        $property = $reflector->getProperty('order_type');
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
            [ 'ColumnName ASC', 'ColumnName', Order::ASCENDING ],
            [ 'ColumnName DESC', 'ColumnName', Order::DESCENDING ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected    期待値
     * @param string $column_name コンストラクタの引数 column_name に渡す値
     * @param int $order_type     コンストラクタの引数 order_type に渡す値
     */
    public function testToString($expected, $column_name, $order_type)
    {
        $this->assertSame($expected, (string)new Order($column_name, $order_type));
    }
}
