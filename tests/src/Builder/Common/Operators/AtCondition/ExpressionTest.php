<?php
namespace Yukar\Sql\Tests\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Builder\Common\Operators\AtCondition\Expression;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス Expression の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Operators\AtCondition
 * @author hiroki sugawara
 */
class ExpressionTest extends CustomizedTestCase
{
    private const PROP_NAME_VALUE = 'value';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return Expression::class;
    }

    /**
     * メソッド testGetValue のデータプロバイダー
     *
     * @return array
     */
    public function providerGetValue(): array
    {
        return [
            [ 'value', 'value' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetValue
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ value の値
     */
    public function testGetValue($expected, $prop_value): void
    {
        /** @var Expression $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_VALUE)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getValue());
    }

    /**
     * メソッド testSetValue データプロバイダー
     *
     * @return array
     */
    public function providerSetValue(): array
    {
        return [
            [ 'value', null, 'value' ],
            [ '0', null, 0 ],
            [ '1', null, 1 ],
            [ '0', null, '0' ],
            [ '0.0', null, '0.0' ],
            [ '1', null, '1' ],
            [ 'xyz', 'value', 'xyz' ],
            [ '0', 'value', 0 ],
            [ '1', 'value', 1 ],
            [ '0', 'value', '0' ],
            [ '0.0', 'value', '0.0' ],
            [ '1', 'value', '1' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetValue
     *
     * @param string $expected   期待値
     * @param mixed  $prop_value プロパティ value の値
     * @param string $value      メソッド setValue の引数 value に渡す値
     */
    public function testSetValue($expected, $prop_value, $value): void
    {
        /** @var Expression $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_VALUE);
        $reflector->setValue($object, $prop_value);
        $object->setValue($value);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetValueFailure データプロバイダー
     *
     * @return array
     */
    public function providerSetValueFailure(): array
    {
        return [
            [ \InvalidArgumentException::class, null, '' ],
            [ \InvalidArgumentException::class, 'value', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetValueFailure
     *
     * @param \Exception $expected   期待値
     * @param mixed      $prop_value プロパティ value の値
     * @param mixed      $value      メソッド setValue の引数 value に渡す値
     */
    public function testSetValueFailure($expected, $prop_value, $value): void
    {
        $this->expectException($expected);

        /** @var Expression $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_VALUE)->setValue($object, $prop_value);
        $object->setValue($value);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        return [
            [ 'column_a < 1', 'column_a', 1, Expression::SIGN_LT ],
            [ 'column_x <> column_y', 'column_x', 'column_y', Expression::SIGN_NE ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected 期待値
     * @param string $name     コンストラクタの引数 name に渡す値
     * @param string $value    コンストラクタの引数 value に渡す値
     * @param int    $sign     メソッド setSign の引数 sign に渡す値
     */
    public function testToString($expected, $name, $value, $sign): void
    {
        $this->assertSame($expected, (string)(new Expression($name, $value, $sign)));
    }
}
