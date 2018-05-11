<?php
namespace Yukar\Sql\Tests\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Builder\Common\Operators\AtCondition\IsNull;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Not;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス IsNull の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Operators\AtCondition
 * @author hiroki sugawara
 */
class IsNullTest extends CustomizedTestCase
{
    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return IsNull::class;
    }

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
        $is_null = new IsNull($column);
        ($is_not === true) && $is_null = new Not($is_null);

        $this->assertSame($expected, (string)$is_null);
    }
}
