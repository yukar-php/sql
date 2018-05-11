<?php
namespace Yukar\Sql\Tests\Builder\Common\Functions;

use Yukar\Sql\Builder\Common\Functions\Count;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス Count の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Functions
 * @author hiroki sugawara
 */
class CountTest extends CustomizedTestCase
{
    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return Count::class;
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
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
    public function testToString($expected, $column, $filter): void
    {
        $this->assertSame($expected, (string)(isset($column) ? new Count($column, $filter) : new Count()));
    }
}
