<?php
namespace Yukar\Sql\Tests\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Builder\Common\Operators\AtCondition\IsNull;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Not;

/**
 * クラス IsNull の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Operators\AtCondition
 * @author hiroki sugawara
 */
class IsNullTest extends \PHPUnit_Framework_TestCase
{
    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
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
     * @param bool   $is_not   コンストラクタの引数 is_not に渡す値
     */
    public function testToString($expected, $column, $is_not): void
    {
        $is_null = new IsNull($column, $is_not);
        ($is_not === true) && $is_null = new Not($is_null);

        $this->assertSame($expected, (string)$is_null);
    }
}
