<?php
namespace Yukar\Sql\Tests\Builder\Operators;

use Yukar\Sql\Builder\Operators\IsNull;

/**
 * クラス IsNull の単体テスト
 *
 * @author hiroki sugawara
 */
class IsNullTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_COLUMN = 'column';
    const PROP_NAME_IS_NOT = 'is_not';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return object コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getIsNullInstance()
    {
        return (new \ReflectionClass(IsNull::class))->newInstanceWithoutConstructor();
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
    public function testGetOperatorFormat()
    {
        $this->assertSame('%s IS %sNULL', $this->getIsNullInstance()->getOperatorFormat());
    }

    /**
     * メソッド testGetColumn のデータプロバイダー
     *
     * @return array
     */
    public function providerGetColumn()
    {
        return [
            [ 'name', 'name' ],
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
        $object = $this->getIsNullInstance();
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
            [ 'name', null, 'name' ],
            [ 'column', 'name', 'column' ],
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
        $object = $this->getIsNullInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_COLUMN);
        $reflector->setValue($object, $prop_value);
        $object->setColumn($column);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetColumnFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumnFailure()
    {
        return [
            [ '\InvalidArgumentException', null, '' ],
            [ '\InvalidArgumentException', null, '0' ],
            [ '\InvalidArgumentException', null, 0 ],
            [ '\InvalidArgumentException', null, 0.0 ],
            [ '\InvalidArgumentException', '1', '' ],
            [ '\InvalidArgumentException', '1', '0' ],
            [ '\InvalidArgumentException', '1', 0 ],
            [ '\InvalidArgumentException', '1', 0.0 ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetColumnFailure
     *
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ column の値
     * @param string $column    メソッド setColumn の引数 column に渡す値
     */
    public function testSetColumnFailure($expected, $prop_value, $column)
    {
        $this->expectException($expected);

        $object = $this->getIsNullInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN)->setValue($object, $prop_value);
        $object->setColumn($column);
    }

    /**
     * メソッド testIsNot のデータプロバイダー
     *
     * @return array
     */
    public function providerIsNot()
    {
        return [
            [ true, true ],
            [ false, false ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerIsNot
     *
     * @param bool $expected   期待値
     * @param bool $prop_value プロパティ is_not の値
     */
    public function testIsNot($expected, $prop_value)
    {
        $object = $this->getIsNullInstance();
        $this->getProperty($object, self::PROP_NAME_IS_NOT)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->isNot());
    }

    /**
     * メソッド testSetIsNot のデータプロバイダー
     *
     * @return array
     */
    public function providerSetIsNot()
    {
        return [
            [ true, false, true ],
            [ false, false, false ],
            [ true, true, true ],
            [ false, true, false ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetIsNot
     *
     * @param bool $expected   期待値
     * @param bool $prop_value プロパティ is_not の値
     * @param bool $is_not     メソッド setIsNot の引数 is_not に渡す値
     */
    public function testSetIsNot($expected, $prop_value, $is_not)
    {
        $object = $this->getIsNullInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_IS_NOT);
        $reflector->setValue($object, $prop_value);
        $object->setIsNot($is_not);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        return [
            [ 'a IS NULL', 'a', false ],
            [ 'a IS NOT NULL', 'a', true ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected 期待値
     * @param string $column   コンストラクタの引数 column に渡す値
     * @param bool $is_not     コンストラクタの引数 is_not に渡す値
     */
    public function testToString($expected, $column, $is_not)
    {
        $this->assertSame($expected, (string)(new IsNull($column, $is_not)));
    }
}
