<?php
namespace Yukar\Sql\Tests\Builder\Common\Functions;

use Yukar\Sql\Builder\Common\Functions\Sum;

/**
 * クラス Sum の単体テスト
 *
 * @author hiroki sugawara
 */
class SumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        return [
            [ 'SUM(column)', 'column', Sum::FILTER_ALL ],
            [ 'SUM(DISTINCT column)', 'column', Sum::FILTER_DISTINCT ],
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
        self::assertSame($expected, (string)(new Sum($column, $filter)));
    }
}
