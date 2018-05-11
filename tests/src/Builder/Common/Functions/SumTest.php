<?php
namespace Yukar\Sql\Tests\Builder\Common\Functions;

use Yukar\Sql\Builder\Common\Functions\Sum;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス Sum の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Functions
 * @author hiroki sugawara
 */
class SumTest extends CustomizedTestCase
{
    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return Sum::class;
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
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
    public function testToString($expected, $column, $filter): void
    {
        $this->assertSame($expected, (string)(new Sum($column, $filter)));
    }
}
