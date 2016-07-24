<?php
namespace Yukar\Sql\Tests\Builder\Operators\AtCondition;

use Yukar\Sql\Builder\Operators\AtCondition\IsNull;

/**
 * クラス IsNull の単体テスト
 *
 * @author hiroki sugawara
 */
class IsNullTest extends \PHPUnit_Framework_TestCase
{
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
        self::assertSame($expected, (string)(new IsNull($column, $is_not)));
    }
}
