<?php
namespace Yukar\Sql\Tests\Builder\Common\Functions;

use Yukar\Sql\Builder\Common\Functions\Avg;

/**
 * クラス Avg の単体テスト
 *
 * @author hiroki sugawara
 */
class AvgTest extends \PHPUnit_Framework_TestCase
{
    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        return [
            [ 'AVG(column)', 'column', Avg::FILTER_ALL ],
            [ 'AVG(DISTINCT column)', 'column', Avg::FILTER_DISTINCT ],
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
    public function testToString($expected, $column, $filter): void
    {
        $this->assertSame($expected, (string)(new Avg($column, $filter)));
    }
}
