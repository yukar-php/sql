<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Sets;

use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Statements\Dml\Select;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Builder\Common\Statements\Sets\Except;
use Yukar\Sql\Interfaces\Builder\Common\Statements\ISelectQuery;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス Except の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Statements\Sets
 * @author hiroki sugawara
 */
class ExceptTest extends CustomizedTestCase
{
    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return Except::class;
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        $first_query = new Select(new From(new Table('first_table')));
        $second_query = new Select(new From(new Table('second_table')));

        return [
            [ 'SELECT * FROM first_table EXCEPT SELECT * FROM second_table', $first_query, $second_query ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string       $expected     期待値
     * @param ISelectQuery $first_query  コンストラクタの引数 first_query に渡す値
     * @param ISelectQuery $second_query コンストラクタの引数 second_query に渡す値
     */
    public function testToString($expected, $first_query, $second_query): void
    {
        $this->assertSame($expected, (string)(new Except($first_query, $second_query)));
    }
}
