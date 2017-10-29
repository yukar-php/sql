<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Dml;

use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Operators\Alias;
use Yukar\Sql\Builder\Common\Statements\Dml\BaseSqlDMLQuery;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Builder\Common\Statements\Phrases\Into;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ISqlQuerySource;

/**
 * 抽象クラス BaseSqlDMLQuery の単体テスト
 *
 * @author hiroki sugawara
 */
class BaseSqlDMLQueryTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_SQL_QUERY_SOURCE = 'sql_query_source';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return BaseSqlDMLQuery コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): BaseSqlDMLQuery
    {
        /** @var BaseSqlDMLQuery $instance */
        $instance = (new \ReflectionClass($this->getMockForAbstractClass(BaseSqlDMLQuery::class)))
            ->newInstanceWithoutConstructor();

        return $instance;
    }

    /**
     * 単体テスト対象となるクラスの指定した名前のプロパティのリクレクションインスタンスを取得します。
     *
     * @param object $object        単体テスト対象となるクラスのインスタンス
     * @param string $property_name リフレクションを取得するプロパティの名前
     *
     * @return \ReflectionProperty 指定した名前のプロパティのリフレクションを持つインスタンス
     */
    private function getProperty($object, string $property_name): \ReflectionProperty
    {
        $property = (new \ReflectionClass($object))->getParentClass()->getProperty($property_name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * メソッド testGetSqlQuerySource のデータプロバイダー
     *
     * @return array
     */
    public function providerGetSqlQuerySource()
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
    public function testGetSqlQuerySource($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_SQL_QUERY_SOURCE)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getSqlQuerySource());
    }

    /**
     * メソッド testSetSqlQuerySource のデータプロバイダー
     *
     * @return array
     */
    public function providerSetSqlQuerySource()
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
    public function testSetSqlQuerySource($expected, $prop_value, $sql_query_source)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_SQL_QUERY_SOURCE);
        $reflector->setValue($object, $prop_value);
        $object->setSqlQuerySource($sql_query_source);

        self::assertSame($expected, $reflector->getValue($object));
    }
}
