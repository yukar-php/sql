<?php
namespace Yukar\Sql\Tests\Builder\Functions;

use Yukar\Sql\Builder\Functions\Count;

/**
 * クラス Count の単体テスト
 *
 * @author hiroki sugawara
 */
class CountTest extends \PHPUnit_Framework_TestCase
{
    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        return [
            [ 'COUNT(*)', null, Count::FILTER_ALL ],
            [ 'COUNT(column)', 'column', Count::FILTER_ALL ],
            [ 'COUNT(DISTINCT column)', 'column', Count::FILTER_DISTINCT ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected 期待値
     * @param string $column   コンストラクタの引数 column に渡す値
     * @param int    $filter   コンストラクタの引数 filter に渡す値
     */
    public function testToString($expected, $column, $filter)
    {
        self::assertSame($expected, (string)(isset($column) ? new Count($column, $filter) : new Count()));
    }
}
