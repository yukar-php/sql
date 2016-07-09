<?php
namespace Yukar\Sql\Tests\Builder\Operators;

use Yukar\Sql\Builder\Operators\Expression;

/**
 * クラス Expression の単体テスト
 *
 * @author hiroki sugawara
 */
class ExpressionTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_NAME = 'name';
    const PROP_NAME_VALUE = 'value';
    const PROP_NAME_SIGN = 'sign';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Expression コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getExpressionInstance(): Expression
    {
        return (new \ReflectionClass('Yukar\Sql\Builder\Operators\Expression'))->newInstanceWithoutConstructor();
    }

    /**
     * 単体テスト対象となるクラスの指定した名前のプロパティのリクレクションインスタンスを取得します。
     *
     * @param Expression $object    単体テスト対象となるクラスのインスタンス
     * @param string $property_name リフレクションを取得するプロパティの名前
     *
     * @return \ReflectionProperty 指定した名前のプロパティのリフレクションを持つインスタンス
     */
    private function getProperty(Expression $object, string $property_name): \ReflectionProperty
    {
        $property = (new \ReflectionClass($object))->getProperty($property_name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * 正常系テスト
     */
    public function testGetFunctionFormat()
    {
        $this->assertSame('%s %s %s', $this->getExpressionInstance()->getOperatorFormat());
    }

    /**
     * メソッド testGetName のデータプロバイダー
     *
     * @return array
     */
    public function providerGetName()
    {
        return [
            [ 'column', 'column' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetName
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ name の値
     */
    public function testGetName($expected, $prop_value)
    {
        $object = $this->getExpressionInstance();
        $this->getProperty($object, self::PROP_NAME_NAME)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getName());
    }

    /**
     * メソッド testSetName のデータプロバイダー
     *
     * @return array
     */
    public function providerSetName()
    {
        return [
            [ 'column', null, 'column' ],
            [ 'abc', 'column', 'abc' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetName
     *
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ name の値
     * @param string $name      メソッド setName の引数 name に渡す値
     */
    public function testSetName($expected, $prop_value, $name)
    {
        $object = $this->getExpressionInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_NAME);
        $reflector->setValue($object, $prop_value);
        $object->setName($name);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetNameFailure()
    {
        return [
            [ '\InvalidArgumentException', null, '' ],
            [ '\InvalidArgumentException', 'column', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetNameFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ name の値
     * @param mixed $name          メソッド setName の引数 name に渡す値
     */
    public function testSetNameFailure($expected, $prop_value, $name)
    {
        $this->expectException($expected);

        $object = $this->getExpressionInstance();
        $this->getProperty($object, self::PROP_NAME_NAME)->setValue($object, $prop_value);
        $object->setName($name);
    }

    /**
     * メソッド testGetValue のデータプロバイダー
     *
     * @return array
     */
    public function providerGetValue()
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
    public function testGetValue($expected, $prop_value)
    {
        $object = $this->getExpressionInstance();
        $this->getProperty($object, self::PROP_NAME_VALUE)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getValue());
    }

    /**
     * メソッド testSetValue データプロバイダー
     *
     * @return array
     */
    public function providerSetValue()
    {
        return [
            [ 'value', null, 'value' ],
            [ 'xyz', 'value', 'xyz' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetValue
     *
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ value の値
     * @param string $value     メソッド setValue の引数 value に渡す値
     */
    public function testSetValue($expected, $prop_value, $value)
    {
        $object = $this->getExpressionInstance();
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
    public function providerSetValueFailure()
    {
        return [
            [ '\InvalidArgumentException', null, '' ],
            [ '\InvalidArgumentException', null, 0 ],
            [ '\InvalidArgumentException', null, 0.0 ],
            [ '\InvalidArgumentException', 'value', '' ],
            [ '\InvalidArgumentException', 'value', '0' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetValueFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ value の値
     * @param mixed $value         メソッド setValue の引数 value に渡す値
     */
    public function testSetValueFailure($expected, $prop_value, $value)
    {
        $this->expectException($expected);

        $object = $this->getExpressionInstance();
        $this->getProperty($object, self::PROP_NAME_VALUE)->setValue($object, $prop_value);
        $object->setValue($value);
    }

    /**
     * メソッド testGetSign データプロバイダー
     *
     * @return array
     */
    public function providerGetSign()
    {
        return [
            [ '=', Expression::SIGNS[Expression::SIGN_EQ] ],
            [ '=', Expression::SIGNS[1] ],
            [ '<>', Expression::SIGNS[Expression::SIGN_NE] ],
            [ '<>', Expression::SIGNS[2] ],
            [ '>', Expression::SIGNS[Expression::SIGN_GT] ],
            [ '>', Expression::SIGNS[3] ],
            [ '>=', Expression::SIGNS[Expression::SIGN_AO] ],
            [ '>=', Expression::SIGNS[4] ],
            [ '<', Expression::SIGNS[Expression::SIGN_LT] ],
            [ '<', Expression::SIGNS[5] ],
            [ '<=', Expression::SIGNS[Expression::SIGN_OU] ],
            [ '<=', Expression::SIGNS[6] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetSign
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ sign の値
     */
    public function testGetSign($expected, $prop_value)
    {
        $object = $this->getExpressionInstance();
        $this->getProperty($object, self::PROP_NAME_SIGN)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getSign());
    }

    /**
     * メソッド testSetSign データプロバイダー
     *
     * @return array
     */
    public function providerSetSign()
    {
        return [
            [ '=', null, Expression::SIGN_EQ ],
            [ '<>', Expression::SIGN_EQ, Expression::SIGN_NE ],
            [ '>', 2, Expression::SIGN_GT ],
            [ '>=', Expression::SIGN_GT, 4 ],
            [ '<', 4, 5 ],
            [ '<=', null, 6 ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetSign
     *
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ sign の値
     * @param int $sign         メソッド setSign の引数 sign に渡す値
     */
    public function testSetSign($expected, $prop_value, $sign)
    {
        $object = $this->getExpressionInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_SIGN);
        $reflector->setValue($object, $prop_value);

        $this->assertInstanceOf(get_class($object), $object->setSign($sign));
        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetSignFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetSignFailure()
    {
        return [
            [ '\InvalidArgumentException', null, 0 ],
            [ '\InvalidArgumentException', Expression::SIGN_NE, 0 ],
            [ '\InvalidArgumentException', null, 7 ],
            [ '\InvalidArgumentException', Expression::SIGN_LT, 7 ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetSignFailure
     *
     * @param \Exception $expected
     * @param mixed $prop_value
     * @param mixed $sign
     */
    public function testSetSignFailure($expected, $prop_value, $sign)
    {
        $this->expectException($expected);

        $object = $this->getExpressionInstance();
        $this->getProperty($object, self::PROP_NAME_SIGN)->setValue($object, $prop_value);
        $object->setSign($sign);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
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
     * @param int $sign        メソッド setSign の引数 sign に渡す値
     */
    public function testToString($expected, $name, $value, $sign)
    {
        $this->assertSame($expected, (string)(new Expression($name, $value))->setSign($sign));
    }
}
