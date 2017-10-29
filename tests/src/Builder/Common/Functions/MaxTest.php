<?php
namespace Yukar\Sql\Tests\Builder\Common\Functions;

use Yukar\Sql\Builder\Common\Functions\Max;

/**
 * クラス Max の単体テスト
 *
 * @author hiroki sugawara
 */
class MaxTest extends \PHPUnit_Framework_TestCase
{
    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        return [
            [ 'MAX(column)', 'column' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected 期待値
     * @param string $column   コンストラクタの引数 column に渡す値
     */
    public function testToString($expected, $column): void
    {
        self::assertSame($expected, (string)(new Max($column)));
    }
}
