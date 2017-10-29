<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Dml;

use Yukar\Sql\Builder\Common\Objects\Columns;
use Yukar\Sql\Builder\Common\Objects\Conditions;
use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Operators\Alias;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Exists;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Expression;
use Yukar\Sql\Builder\Common\Statements\Dml\Delete;
use Yukar\Sql\Builder\Common\Statements\Dml\Select;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Builder\Common\Statements\Phrases\Into;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ICondition;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ISqlQuerySource;

/**
 * クラス Delete の単体テスト
 *
 * @author hiroki sugawara
 */
class DeleteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Delete コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Delete
    {
        /** @var Delete $instance */
        $instance = (new \ReflectionClass(Delete::class))->newInstanceWithoutConstructor();

        return $instance;
    }

    /**
     * 正常系テスト
     */
    public function testGetQueryFormat()
    {
        self::assertSame('DELETE %s %s', $this->getNewInstance()->getQueryFormat());
    }

    /**
     * メソッド testSetSqlQuerySourceFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetSqlQuerySourceFailure()
    {
        return [
            [ new Table('table_name') ],
            [ new Alias('(SELECT foo FROM table)', 'bar') ],
            [ new Into(new Table('table_name')) ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetSqlQuerySourceFailure
     *
     * @param ISqlQuerySource $sql_query_source メソッド setSqlQuerySource の引数 sql_query_source に渡す値
     */
    public function testSetSqlQuerySourceFailure($sql_query_source)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->getNewInstance()->setSqlQuerySource($sql_query_source);
    }

    /**
     * メソッド testSetWhere のデータプロバイダー
     *
     * @return array
     */
    public function providerSetWhere()
    {
        return [
            [ (new Conditions())->addCondition(new Expression('a', 1)) ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetWhere
     *
     * @param ICondition $condition メソッド setWhere の引数 condition に渡す値
     */
    public function testSetWhere($condition)
    {
        // wrapしているsetWhereメソッドの単体テストは別のところで実施済みなので戻り値のチェックだけをする
        self::assertInstanceOf(Delete::class, $this->getNewInstance()->setWhere($condition));
    }


    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        $from_orig = new From(new Table('table_name'));
        $from_as = new From(new Alias(new Table('table_name'), 'alias_table'));
        $where = (new Conditions())->setConditions(
            new Expression('a', 0, Expression::SIGN_GT),
            new Expression('b', 10, Expression::SIGN_OU)
        );
        $exists_where = (new Conditions())->addCondition(
            new Exists(new Select(new From(new Table('table')), new Columns([ 'col' ])))
        );

        return [
            [ 'DELETE FROM table_name', $from_orig, null ],
            [ 'DELETE FROM table_name AS alias_table', $from_as, null ],
            [ 'DELETE FROM table_name WHERE a > 0 AND b <= 10', $from_orig, $where ],
            [ 'DELETE FROM table_name AS alias_table WHERE a > 0 AND b <= 10', $from_as, $where ],
            [ 'DELETE FROM table_name WHERE EXISTS (SELECT col FROM table)', $from_orig, $exists_where ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected 期待値
     * @param From   $from     コンストラクタの引数 from に渡す値
     * @param mixed  $where    メソッド setWhere の引数 condition に渡す値（null以外の時のみ）
     */
    public function testToString($expected, $from, $where)
    {
        $delete = new Delete($from);
        isset($where) && $delete->setWhere($where);

        self::assertSame($expected, (string)$delete);
    }
}
