<?php
namespace Yukar\Sql\Tests\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Builder\Common\Operators\AtCondition\Like;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Not;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス Like の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Operators\AtCondition
 * @author hiroki sugawara
 */
class LikeTest extends CustomizedTestCase
{
    private const PROP_NAME_PATTERN = 'pattern';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return Like::class;
    }

    /**
     * メソッド testGetPattern のデータプロバイダー
     *
     * @return array
     */
    public function providerGetPattern(): array
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
    public function testGetPattern($expected, $prop_value): void
    {
        /** @var Like $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_PATTERN)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getPattern());
    }

    /**
     * メソッド testSetPattern のデータプロバイダー
     *
     * @return array
     */
    public function providerSetPattern(): array
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
     * @param string $expected   期待値
     * @param mixed  $prop_value プロパティ pattern の値
     * @param string $pattern    メソッド setPattern の引数 pattern に渡す値
     */
    public function testSetPattern($expected, $prop_value, $pattern): void
    {
        /** @var Like $object */
        $object = $this->getNewInstance();
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
    public function providerSetPatternFailure(): array
    {
        return [
            [ \InvalidArgumentException::class, null, '' ],
            [ \InvalidArgumentException::class, 'column', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetPatternFailure
     *
     * @param string $expected   期待値
     * @param mixed  $prop_value プロパティ pattern の値
     * @param string $pattern    メソッド setPattern の引数 pattern に渡す値
     */
    public function testSetPatternFailure($expected, $prop_value, $pattern): void
    {
        $this->expectException($expected);

        /** @var Like $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_PATTERN)->setValue($object, $prop_value);
        $object->setPattern($pattern);
    }

    /**
     * メソッド testSetIsNot のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
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
     * @param bool   $is_not   コンストラクタの引数 is_not に渡す値
     */
    public function testToString($expected, $column, $pattern, $is_not): void
    {
        $like = new Like($column, $pattern);
        ($is_not === true) && $like = new Not($like);

        $this->assertSame($expected, (string)$like);
    }
}
