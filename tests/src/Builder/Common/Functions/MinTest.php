<?php
namespace Yukar\Sql\Tests\Builder\Common\Functions;

use Yukar\Sql\Builder\Common\Functions\Min;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス Min の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Functions
 * @author hiroki sugawara
 */
class MinTest extends CustomizedTestCase
{
    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return Min::class;
    }

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
        $this->assertSame($expected, (string)(new Min($column)));
    }
}
