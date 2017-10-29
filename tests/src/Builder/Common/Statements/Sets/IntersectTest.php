<?php
namespace Yukar\Sql\Tests\Builder\Statements\Sets;

use Yukar\Sql\Builder\Objects\Table;
use Yukar\Sql\Builder\Statements\Dml\Select;
use Yukar\Sql\Builder\Statements\Phrases\From;
use Yukar\Sql\Builder\Statements\Sets\Intersect;
use Yukar\Sql\Interfaces\Builder\Statements\ISelectQuery;

/**
 * クラス Intersect の単体テスト
 *
 * @author hiroki sugawara
 */
class IntersectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        $first_query = new Select(new From(new Table('first_table')));
        $second_query = new Select(new From(new Table('second_table')));

        return [
            [ 'SELECT * FROM first_table INTERSECT SELECT * FROM second_table', $first_query, $second_query ],
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
    public function testToString($expected, $first_query, $second_query)
    {
        self::assertSame($expected, (string)(new Intersect($first_query, $second_query)));
    }
}
