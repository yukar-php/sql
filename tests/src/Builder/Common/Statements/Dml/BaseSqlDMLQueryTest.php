<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Dml;

use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Operators\Alias;
use Yukar\Sql\Builder\Common\Statements\Dml\BaseSqlDMLQuery;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Builder\Common\Statements\Phrases\Into;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ISqlQuerySource;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * 抽象クラス BaseSqlDMLQuery の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Statements\Dml
 * @author hiroki sugawara
 */
class BaseSqlDMLQueryTest extends CustomizedTestCase
{
    private const PROP_NAME_SQL_QUERY_SOURCE = 'sql_query_source';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return BaseSqlDMLQuery::class;
    }

    /**
     * メソッド testGetSqlQuerySource のデータプロバイダー
     *
     * @return array
     */
    public function providerGetSqlQuerySource(): array
    {
        $table_name = new Table('table_name');
        $sub_query_text = new Alias('(SELECT foo FROM table)', 'bar');
        $sql_query_from = new From($table_name);
        $into_table_name = new Into(new Table('table_name'));

        return [
            [ $table_name, $table_name ],
            [ $sub_query_text, $sub_query_text ],
            [ $sql_query_from, $sql_query_from ],
            [ $into_table_name, $into_table_name ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetSqlQuerySource
     *
     * @param string          $expected   期待値
     * @param ISqlQuerySource $prop_value プロパティ sql_query_source の値
     */
    public function testGetSqlQuerySource($expected, $prop_value): void
    {
        /** @var BaseSqlDMLQuery $object */
        $object = $this->getNewAbstractInstance();
        $this->getParentProperty($object, self::PROP_NAME_SQL_QUERY_SOURCE)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getSqlQuerySource());
    }

    /**
     * メソッド testSetSqlQuerySource のデータプロバイダー
     *
     * @return array
     */
    public function providerSetSqlQuerySource(): array
    {
        $table_name = new Table('table_name');
        $sub_query_text = new Alias('(SELECT foo FROM table)', 'bar');
        $sql_query_from = new From($table_name);
        $into_table_name = new Into(new Table('table_name'));

        return [
            [ $table_name, null, $table_name ],
            [ $sub_query_text, null, $sub_query_text ],
            [ $sql_query_from, null, $sql_query_from ],
            [ $into_table_name, null, $into_table_name ],
            [ $table_name, $into_table_name, $table_name ],
            [ $sub_query_text, $table_name, $sub_query_text ],
            [ $sql_query_from, $sub_query_text, $sql_query_from ],
            [ $into_table_name, $sql_query_from, $into_table_name ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetSqlQuerySource
     *
     * @param string          $expected         期待値
     * @param ISqlQuerySource $prop_value       プロパティ sql_query_source の値
     * @param ISqlQuerySource $sql_query_source メソッド setSqlQuerySource の引数 sql_query_source に渡す値
     */
    public function testSetSqlQuerySource($expected, $prop_value, $sql_query_source): void
    {
        /** @var BaseSqlDMLQuery $object */
        $object = $this->getNewAbstractInstance();
        $reflector = $this->getParentProperty($object, self::PROP_NAME_SQL_QUERY_SOURCE);
        $reflector->setValue($object, $prop_value);
        $object->setSqlQuerySource($sql_query_source);

        $this->assertSame($expected, $reflector->getValue($object));
    }
}
