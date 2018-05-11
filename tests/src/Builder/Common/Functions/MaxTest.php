<?php
namespace Yukar\Sql\Tests\Builder\Common\Functions;

use Yukar\Sql\Builder\Common\Functions\Max;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス Max の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Functions
 * @author hiroki sugawara
 */
class MaxTest extends CustomizedTestCase
{
    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return Max::class;
    }

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
        $this->assertSame($expected, (string)(new Max($column)));
    }
}
