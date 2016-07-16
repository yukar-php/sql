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
    const PROP_NAME_PATTERN = 'pattern';
    const PROP_NAME_IS_NOT = 'is_not';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Like コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Like
    {
        /** @var Like $instance */
        $instance = (new \ReflectionClass(Like::class))->newInstanceWithoutConstructor();

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
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_PATTERN)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getPattern());
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
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_PATTERN);
        $reflector->setValue($object, $prop_value);
        $object->setPattern($pattern);

        self::assertSame($expected, $reflector->getValue($object));
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

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_PATTERN)->setValue($object, $prop_value);
        $object->setPattern($pattern);
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
        self::assertSame($expected, (string)(new Like($column, $pattern, $is_not)));
    }
}
