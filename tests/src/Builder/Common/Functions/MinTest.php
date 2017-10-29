<?php
namespace Yukar\Sql\Tests\Builder\Common\Functions;

use Yukar\Sql\Builder\Common\Functions\Min;

/**
 * クラス Min の単体テスト
 *
 * @author hiroki sugawara
 */
class MinTest extends \PHPUnit_Framework_TestCase
{
    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        return [
            [ 'MIN(column)', 'column' ],
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
        self::assertSame($expected, (string)(new Min($column)));
    }
}
