<?php
namespace Yukar\Sql\Tests\Builder\Common\Operators;

use Yukar\Sql\Builder\Common\Operators\Order;

/**
 * クラス Order の単体テスト
 *
 * @author hiroki sugawara
 */
class OrderTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_COLUMN_NAME = 'column_name';
    const PROP_NAME_ORDER_TYPE = 'order_type';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Order コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Order
    {
        /** @var Order $instance */
        $instance = (new \ReflectionClass(Order::class))->newInstanceWithoutConstructor();

        return $instance;
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
    public function testGetOperatorFormat(): void
    {
        $this->assertSame('%s %s', $this->getNewInstance()->getOperatorFormat());
    }

    /**
     * メソッド testGetColumnName のデータプロバイダー
     *
     * @return array
     */
    public function providerGetColumnName(): array
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
    public function testGetColumnName($expected, $prop_value): void
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN_NAME)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getColumnName());
    }

    /**
     * メソッド testSetColumnName のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumnName(): array
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
     * @param mixed  $prop_value  プロパティ column_name の値
     * @param string $column_name メソッド setColumnName の引数 column_name に渡す値
     */
    public function testSetColumnName($expected, $prop_value, $column_name): void
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_COLUMN_NAME);
        $reflector->setValue($object, $prop_value);
        $object->setColumnName($column_name);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetColumnNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumnNameFailure(): array
    {
        return [
            [ \InvalidArgumentException::class, null, '' ],
            [ \InvalidArgumentException::class, 'TableName', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetColumnNameFailure
     *
     * @param \Exception $expected    期待値
     * @param mixed      $prop_value  プロパティ column_name の値
     * @param mixed      $column_name メソッド setColumnName の引数 column_name に渡す値
     */
    public function testSetColumnNameFailure($expected, $prop_value, $column_name): void
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN_NAME)->setValue($object, $prop_value);
        $object->setColumnName($column_name);
    }

    /**
     * メソッド testGetOrderType のデータプロバイダー
     *
     * @return array
     */
    public function providerGetOrderType(): array
    {
        return [
            [ 'ASC', null ],
            [ 'ASC', 'ASC' ],
            [ 'DESC', 'DESC' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetOrderType
     *
     * @param string $expected   期待値
     * @param mixed  $prop_value プロパティ order_type の値
     */
    public function testGetOrderType($expected, $prop_value): void
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_ORDER_TYPE)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getOrderType());
    }

    /**
     * メソッド testSetOrderType のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOrderType(): array
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
     * @param string $expected   期待値
     * @param mixed  $prop_value プロパティ order_type の値
     * @param int    $order_type メソッド setOrderType の引数 order_type に渡す値
     */
    public function testSetOrderType($expected, $prop_value, $order_type): void
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_ORDER_TYPE);
        $reflector->setValue($object, $prop_value);

        $this->assertInstanceOf(get_class($object), $object->setOrderType($order_type));
        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
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
     * @param int    $order_type  コンストラクタの引数 order_type に渡す値
     */
    public function testToString($expected, $column_name, $order_type): void
    {
        $this->assertSame($expected, (string)new Order($column_name, $order_type));
    }
}
