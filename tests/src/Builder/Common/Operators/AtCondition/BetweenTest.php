<?php
namespace Yukar\Sql\Tests\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Builder\Common\Operators\AtCondition\Between;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Not;

/**
 * クラス Between の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Operators\AtCondition
 * @author hiroki sugawara
 */
class BetweenTest extends \PHPUnit_Framework_TestCase
{
    private const PROP_NAME_FROM_VALUE = 'from_value';
    private const PROP_NAME_TO_VALUE = 'to_value';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Between コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Between
    {
        /** @var Between $instance */
        $instance = (new \ReflectionClass(Between::class))->newInstanceWithoutConstructor();

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
     * メソッド testGetFromValue のデータプロバイダー
     *
     * @return array
     */
    public function providerGetFromValue(): array
    {
        return [
            [ '1', '1' ],
            [ 'abc', 'abc' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetFromValue
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ form_value の値
     */
    public function testGetFromValue($expected, $prop_value): void
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_FROM_VALUE)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getFromValue());
    }

    /**
     * メソッド testSetFromValue のデータプロバイダー
     *
     * @return array
     */
    public function providerSetFromValue(): array
    {
        return [
            [ '1', null, '1' ],
            [ 'column', null, 'column' ],
            [ '2', '1', '2' ],
            [ 'value', 'column', 'value' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetFromValue
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ form_value の値
     * @param string $from_value メソッド setFromValue の引数 from_value に渡す値
     */
    public function testSetFromValue($expected, $prop_value, $from_value): void
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_FROM_VALUE);
        $reflector->setValue($object, $prop_value);
        $object->setFromValue($from_value);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetFromValueFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetFromValueFailure(): array
    {
        return [
            [ \InvalidArgumentException::class, null, '0' ],
            [ \InvalidArgumentException::class, null, '' ],
            [ \InvalidArgumentException::class, null, 0 ],
            [ \InvalidArgumentException::class, null, 0.0 ],
            [ \InvalidArgumentException::class, '1', '0' ],
            [ \InvalidArgumentException::class, '1', '' ],
            [ \InvalidArgumentException::class, '1', 0 ],
            [ \InvalidArgumentException::class, '1', 0.0 ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetFromValueFailure
     *
     * @param \Exception $expected   期待値
     * @param mixed      $prop_value プロパティ form_value の値
     * @param string     $from_value メソッド setFromValue の引数 from_value に渡す値
     */
    public function testSetFromValueFailure($expected, $prop_value, $from_value): void
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_FROM_VALUE)->setValue($object, $prop_value);
        $object->setFromValue($from_value);
    }

    /**
     * メソッド testGetToValue のデータプロバイダー
     *
     * @return array
     */
    public function providerGetToValue(): array
    {
        return [
            [ '1', '1' ],
            [ 'abc', 'abc' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetToValue
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ to_value の値
     */
    public function testGetToValue($expected, $prop_value): void
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_TO_VALUE)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getToValue());
    }

    /**
     * メソッド testSetToValue のデータプロバイダー
     *
     * @return array
     */
    public function providerSetToValue(): array
    {
        return [
            [ '1', null, '1' ],
            [ 'column', null, 'column' ],
            [ '2', '1', '2' ],
            [ 'value', 'column', 'value' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetToValue
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ to_value の値
     * @param string $from_value メソッド setToValue の引数 to_value に渡す値
     */
    public function testSetToValue($expected, $prop_value, $from_value): void
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_TO_VALUE);
        $reflector->setValue($object, $prop_value);
        $object->setToValue($from_value);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetToValueFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetToValueFailure(): array
    {
        return [
            [ \InvalidArgumentException::class, null, '0' ],
            [ \InvalidArgumentException::class, null, '' ],
            [ \InvalidArgumentException::class, null, 0 ],
            [ \InvalidArgumentException::class, null, 0.0 ],
            [ \InvalidArgumentException::class, '1', '0' ],
            [ \InvalidArgumentException::class, '1', '' ],
            [ \InvalidArgumentException::class, '1', 0 ],
            [ \InvalidArgumentException::class, '1', 0.0 ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetToValueFailure
     *
     * @param \Exception $expected   期待値
     * @param mixed      $prop_value プロパティ form_value の値
     * @param string     $from_value メソッド setFromValue の引数 from_value に渡す値
     */
    public function testSetToValueFailure($expected, $prop_value, $from_value): void
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_TO_VALUE)->setValue($object, $prop_value);
        $object->setToValue($from_value);
    }

    /**
     * メソッド  のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        return [
            [ 'a BETWEEN 1 AND 2', 'a', '1', '2', false ],
            [ 'b BETWEEN 1 AND 2', 'b', 1, 2, false ],
            [ 'a NOT BETWEEN 1 AND 2', 'a', '1', '2', true ],
            [ 'b NOT BETWEEN 1 AND 2', 'b', 1, 2, true ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected   期待値
     * @param string $column     コンストラクタの引数 column に渡す値
     * @param string $from_value コンストラクタの引数 from_value に渡す値
     * @param string $to_value   コンストラクタの引数 to_value に渡す値
     * @param bool   $is_not     コンストラクタの引数 is_not に渡す値
     */
    public function testToString($expected, $column, $from_value, $to_value, $is_not): void
    {
        $between = new Between($column, $from_value, $to_value);
        ($is_not === true) && $between = new Not($between);

        $this->assertSame($expected, (string)$between);
    }
}
