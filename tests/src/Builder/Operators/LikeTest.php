<?php
namespace Yukar\Sql\Tests\Builder\Operators;

use Yukar\Sql\Builder\Operators\Like;

/**
 * クラス Like の単体テスト
 *
 * @author hiroki sugawara
 */
class LikeTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_COLUMN = 'column';
    const PROP_NAME_PATTERN = 'pattern';
    const PROP_NAME_IS_NOT = 'is_not';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return object コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getLikeInstance()
    {
        return (new \ReflectionClass(Like::class))->newInstanceWithoutConstructor();
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
        $this->assertSame('%s %sLIKE \'%s\'', $this->getLikeInstance()->getOperatorFormat());
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
        $object = $this->getLikeInstance();
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
            [ 'set', 'column', 'set' ],
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
        $object = $this->getLikeInstance();
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
            [ '\InvalidArgumentException', 'column', '' ],
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

        $object = $this->getLikeInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN)->setValue($object, $prop_value);
        $object->setColumn($column);
    }

    /**
     * メソッド testGetPattern のデータプロバイダー
     *
     * @return array
     */
    public function providerGetPattern()
    {
        return [
            [ '_col%', '_col%' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetPattern
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ pattern の値
     */
    public function testGetPattern($expected, $prop_value)
    {
        $object = $this->getLikeInstance();
        $this->getProperty($object, self::PROP_NAME_PATTERN)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getPattern());
    }

    /**
     * メソッド testSetPattern のデータプロバイダー
     *
     * @return array
     */
    public function providerSetPattern()
    {
        return [
            [ 'column', null, 'column' ],
            [ 'set', 'column', 'set' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetPattern
     *
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ pattern の値
     * @param string $pattern   メソッド setPattern の引数 pattern に渡す値
     */
    public function testSetPattern($expected, $prop_value, $pattern)
    {
        $object = $this->getLikeInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_PATTERN);
        $reflector->setValue($object, $prop_value);
        $object->setPattern($pattern);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetPatternFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetPatternFailure()
    {
        return [
            [ '\InvalidArgumentException', null, '' ],
            [ '\InvalidArgumentException', 'column', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetPatternFailure
     *
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ pattern の値
     * @param string $pattern   メソッド setPattern の引数 pattern に渡す値
     */
    public function testSetPatternFailure($expected, $prop_value, $pattern)
    {
        $this->expectException($expected);

        $object = $this->getLikeInstance();
        $this->getProperty($object, self::PROP_NAME_PATTERN)->setValue($object, $prop_value);
        $object->setPattern($pattern);
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
        $object = $this->getLikeInstance();
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
        $object = $this->getLikeInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_IS_NOT);
        $reflector->setValue($object, $prop_value);
        $object->setIsNot($is_not);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetIsNot のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        return [
            [ "a LIKE '%bcd_'", 'a', '%bcd_', false ],
            [ "b NOT LIKE '%bcd_'", 'b', '%bcd_', true ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected 期待値
     * @param string $column   コンストラクタの引数 column に渡す値
     * @param string $pattern  コンストラクタの引数 pattern に渡す値
     * @param bool $is_not     コンストラクタの引数 is_not に渡す値
     */
    public function testToString($expected, $column, $pattern, $is_not)
    {
        $this->assertSame($expected, (string)(new Like($column, $pattern, $is_not)));
    }
}
