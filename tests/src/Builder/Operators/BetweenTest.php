<?php
namespace Yukar\Sql\Tests\Builder\Operators;

use Yukar\Sql\Builder\Operators\Between;

/**
 * クラス Between の単体テスト
 *
 * @author hiroki sugawara
 */
class BetweenTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_COLUMN = 'column';
    const PROP_NAME_FROM_VALUE = 'from_value';
    const PROP_NAME_TO_VALUE = 'to_value';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Between コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getBetweenInstance(): Between
    {
        return (new \ReflectionClass('Yukar\Sql\Builder\Operators\Between'))->newInstanceWithoutConstructor();
    }

    /**
     * 単体テスト対象となるクラスの指定した名前のプロパティのリクレクションインスタンスを取得します。
     *
     * @param Between $object       単体テスト対象となるクラスのインスタンス
     * @param string $property_name リフレクションを取得するプロパティの名前
     *
     * @return \ReflectionProperty 指定した名前のプロパティのリフレクションを持つインスタンス
     */
    private function getProperty(Between $object, string $property_name): \ReflectionProperty
    {
        $property = (new \ReflectionClass($object))->getProperty($property_name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * 正常系テスト
     */
    public function testGetOperatorFormat()
    {
        $this->assertSame('%s BETWEEN %s AND %s', $this->getBetweenInstance()->getOperatorFormat());
    }

    /**
     * メソッド testGetColumn のデータプロバイダー
     *
     * @return array
     */
    public function providerGetColumn()
    {
        return [
            [ 'column', 'column' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetColumn
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ column の値
     */
    public function testGetColumn($expected, $prop_value)
    {
        $object = $this->getBetweenInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getColumn());
    }

    /**
     * メソッド testSetColumn のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumn()
    {
        return [
            [ 'column', null, 'column' ],
            [ 'column', 'source', 'column' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetColumn
     *
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ column の値
     * @param string $column    メソッド setColumn の引数 column に渡す値
     */
    public function testSetColumn($expected, $prop_value, $column)
    {
        $object = $this->getBetweenInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_COLUMN);
        $reflector->setValue($object, $prop_value);
        $object->setColumn($column);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetColumn のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumnFailure()
    {
        return [
            [ '\InvalidArgumentException', null, '' ],
            [ '\InvalidArgumentException', 'column', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetColumnFailure
     *
     * @param \Exception $expected   期待値
     * @param mixed $prop_value      プロパティ column の値
     * @param string $column         メソッド setColumn の引数 column に渡す値
     */
    public function testSetColumnFailure($expected, $prop_value, $column)
    {
        $this->expectException($expected);

        $object = $this->getBetweenInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN)->setValue($object, $prop_value);
        $object->setColumn($column);
    }

    /**
     * メソッド testGetFromValue のデータプロバイダー
     *
     * @return array
     */
    public function providerGetFromValue()
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
    public function testGetFromValue($expected, $prop_value)
    {
        $object = $this->getBetweenInstance();
        $this->getProperty($object, self::PROP_NAME_FROM_VALUE)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getFromValue());
    }

    /**
     * メソッド testSetFromValue のデータプロバイダー
     *
     * @return array
     */
    public function providerSetFromValue()
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
    public function testSetFromValue($expected, $prop_value, $from_value)
    {
        $object = $this->getBetweenInstance();
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
    public function providerSetFromValueFailure()
    {
        return [
            [ '\InvalidArgumentException', null, '0' ],
            [ '\InvalidArgumentException', null, '' ],
            [ '\InvalidArgumentException', null, 0 ],
            [ '\InvalidArgumentException', null, 0.0 ],
            [ '\InvalidArgumentException', '1', '0' ],
            [ '\InvalidArgumentException', '1', '' ],
            [ '\InvalidArgumentException', '1', 0 ],
            [ '\InvalidArgumentException', '1', 0.0 ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetFromValueFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ form_value の値
     * @param string $from_value   メソッド setFromValue の引数 from_value に渡す値
     */
    public function testSetFromValueFailure($expected, $prop_value, $from_value)
    {
        $this->expectException($expected);

        $object = $this->getBetweenInstance();
        $this->getProperty($object, self::PROP_NAME_FROM_VALUE)->setValue($object, $prop_value);
        $object->setFromValue($from_value);
    }

    /**
     * メソッド testGetToValue のデータプロバイダー
     *
     * @return array
     */
    public function providerGetToValue()
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
    public function testGetToValue($expected, $prop_value)
    {
        $object = $this->getBetweenInstance();
        $this->getProperty($object, self::PROP_NAME_TO_VALUE)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getToValue());
    }

    /**
     * メソッド testSetToValue のデータプロバイダー
     *
     * @return array
     */
    public function providerSetToValue()
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
    public function testSetToValue($expected, $prop_value, $from_value)
    {
        $object = $this->getBetweenInstance();
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
    public function providerSetToValueFailure()
    {
        return [
            [ '\InvalidArgumentException', null, '0' ],
            [ '\InvalidArgumentException', null, '' ],
            [ '\InvalidArgumentException', null, 0 ],
            [ '\InvalidArgumentException', null, 0.0 ],
            [ '\InvalidArgumentException', '1', '0' ],
            [ '\InvalidArgumentException', '1', '' ],
            [ '\InvalidArgumentException', '1', 0 ],
            [ '\InvalidArgumentException', '1', 0.0 ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetToValueFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ form_value の値
     * @param string $from_value   メソッド setFromValue の引数 from_value に渡す値
     */
    public function testSetToValueFailure($expected, $prop_value, $from_value)
    {
        $this->expectException($expected);

        $object = $this->getBetweenInstance();
        $this->getProperty($object, self::PROP_NAME_TO_VALUE)->setValue($object, $prop_value);
        $object->setToValue($from_value);
    }

    /**
     * メソッド  のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        return [
            [ 'a BETWEEN 1 AND 2', 'a', '1', '2' ],
            [ 'b BETWEEN 1 AND 2', 'b', 1, 2 ],
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
     */
    public function testToString($expected, $column, $from_value, $to_value)
    {
        $this->assertSame($expected, (string)(new Between($column, $from_value, $to_value)));
    }
}
