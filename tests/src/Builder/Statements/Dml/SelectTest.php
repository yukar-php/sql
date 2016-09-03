<?php
namespace Yukar\Sql\Tests\Builder\Statements\Dml;

use Yukar\Sql\Builder\Objects\Columns;
use Yukar\Sql\Builder\Objects\Conditions;
use Yukar\Sql\Builder\Objects\Table;
use Yukar\Sql\Builder\Operators\Alias;
use Yukar\Sql\Builder\Operators\AtCondition\Expression;
use Yukar\Sql\Builder\Operators\Order;
use Yukar\Sql\Builder\Statements\Dml\Select;
use Yukar\Sql\Builder\Statements\Phrases\From;
use Yukar\Sql\Builder\Statements\Phrases\GroupBy;
use Yukar\Sql\Builder\Statements\Phrases\Into;
use Yukar\Sql\Builder\Statements\Phrases\Join;
use Yukar\Sql\Builder\Statements\Phrases\OrderBy;
use Yukar\Sql\Interfaces\Builder\Objects\IColumns;
use Yukar\Sql\Interfaces\Builder\Objects\ICondition;
use Yukar\Sql\Interfaces\Builder\Objects\ISqlQuerySource;

/**
 * クラス Select の単体テスト
 *
 * @author hiroki sugawara
 */
class SelectTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_COLUMNS = 'columns';
    const PROP_NAME_JOIN = 'join';
    const PROP_NAME_GROUP_BY = 'group_by';
    const PROP_NAME_ORDER_BY = 'order_by';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Select コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Select
    {
        /** @var Select $instance */
        $instance = (new \ReflectionClass(Select::class))->newInstanceWithoutConstructor();

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
        $property = (new \ReflectionClass($object))->getProperty($property_name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * 正常系テスト
     */
    public function testGetQueryFormat()
    {
        self::assertSame('SELECT %s %s %s', $this->getNewInstance()->getQueryFormat());
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
     * メソッド testGetColumns のデータプロバイダー
     *
     * @return array
     */
    public function providerGetColumns()
    {
        $columns = new Columns([ 'a', 'b', 'c' ]);

        return [
            [ $columns, $columns ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetColumns
     *
     * @param IColumns $expected   期待値
     * @param IColumns $prop_value プロパティ columns の値
     */
    public function testGetColumns($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMNS)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getColumns());
    }

    /**
     * メソッド testSetColumns のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumns()
    {
        $normal_columns = new Columns([ 'a', 'b', 'c' ]);
        $no_set_columns = new Columns();

        return [
            [ $normal_columns, null, $normal_columns ],
            [ $no_set_columns, null, $no_set_columns ],
            [ $normal_columns, $no_set_columns, $normal_columns ],
            [ $no_set_columns, $normal_columns, $no_set_columns ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetColumns
     *
     * @param IColumns $expected 期待値
     * @param mixed $prop_value  プロパティ columns の値
     * @param IColumns $columns  メソッド setColumns の引数 columns に渡す値
     */
    public function testSetColumns($expected, $prop_value, $columns)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_COLUMNS);
        $reflector->setValue($object, $prop_value);

        self::assertInstanceOf(Select::class, $object->setColumns($columns));
        self::assertSame($expected, $reflector->getValue($object));
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
        self::assertInstanceOf(Select::class, $this->getNewInstance()->setWhere($condition));
    }

    /**
     * メソッド testGetJoin のデータプロバイダー
     *
     * @return array
     */
    public function providerGetJoin()
    {
        $join = (new Join(new Alias(new Table('table_name'), 'b')))->setOnCondition(
            (new Conditions())->addCondition(new Expression('a.column_1', 'b.column_1'))
        );

        return [
            [ $join, $join ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetJoin
     *
     * @param Join $expected   期待値
     * @param Join $prop_value プロパティ join の値
     */
    public function testGetJoin($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_JOIN)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getJoin());
    }

    /**
     * メソッド testSetJoin のデータプロバイダー
     *
     * @return array
     */
    public function providerSetJoin()
    {
        $join_table = new Join(
            new Alias(new Table('table_name'), 'b'),
            Join::INNER_JOIN,
            (new Conditions())->addCondition(new Expression('a.column_1', 'b.column_1'))
        );
        $join_query = new Join(
            new Alias('(SELECT * FROM table_name)', 'b'),
            Join::INNER_JOIN,
            (new Conditions())->addCondition(new Expression('a.column_2', 'b.column_2'))
        );

        return [
            [ $join_table, null, $join_table ],
            [ $join_query, null, $join_query ],
            [ $join_table, $join_query, $join_table ],
            [ $join_query, $join_table, $join_query ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetJoin
     *
     * @param JOIN $expected    期待値
     * @param mixed $prop_value プロパティ join の値
     * @param JOIN $join        メソッド setJoin の引数 join に渡す値
     */
    public function testSetJoin($expected, $prop_value, $join)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_JOIN);
        $reflector->setValue($object, $prop_value);

        self::assertInstanceOf(Select::class, $object->setJoin($join));
        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testGetGroupBy のデータプロバイダー
     *
     * @return array
     */
    public function providerGetGroupBy()
    {
        $group_by = new GroupBy(
            new Columns([ 'a', 'b', 'c' ]),
            (new Conditions())->addCondition(new Expression('a', 1))
        );

        return [
            [ $group_by, $group_by ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetGroupBy
     *
     * @param GroupBy $expected   期待値
     * @param GroupBy $prop_value プロパティ group_by の値
     */
    public function testGetGroupBy($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_GROUP_BY)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getGroupBy());
    }

    /**
     * メソッド testSetGroupBy のデータプロバイダー
     *
     * @return array
     */
    public function providerSetGroupBy()
    {
        $group_by_a = new GroupBy(new Columns([ 'x', 'y' ]));
        $group_by_b = new GroupBy(
            new Columns([ 'a', 'b', 'c' ]),
            (new Conditions())->addCondition(new Expression('a', 1))
        );

        return [
            [ $group_by_a, null, $group_by_a ],
            [ $group_by_b, null, $group_by_b ],
            [ $group_by_a, $group_by_b, $group_by_a ],
            [ $group_by_b, $group_by_a, $group_by_b ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetGroupBy
     *
     * @param GroupBy $expected 期待値
     * @param mixed $prop_value プロパティ group_by の値
     * @param GroupBy $group_by メソッド setGroupBy の引数 group_by に渡す値
     */
    public function testSetGroupBy($expected, $prop_value, $group_by)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_GROUP_BY);
        $reflector->setValue($object, $prop_value);

        self::assertInstanceOf(Select::class, $object->setGroupBy($group_by));
        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testGetOrderBy のデータプロバイダー
     *
     * @return array
     */
    public function providerGetOrderBy()
    {
        $order_by = new OrderBy(new Columns([ new Order('a', Order::DESCENDING) ]));

        return [
            [ $order_by, $order_by ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetOrderBy
     *
     * @param OrderBy $expected   期待値
     * @param OrderBy $prop_value プロパティ order_by の値
     */
    public function testGetOrderBy($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_ORDER_BY)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getOrderBy());
    }

    /**
     * メソッド testSetOrderBy のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOrderBy()
    {
        $order_by_a = new OrderBy(new Columns([ new Order('a') ]));
        $order_by_b = new OrderBy(new Columns([ new Order('b', Order::DESCENDING) ]));

        return [
            [ $order_by_a, null, $order_by_a ],
            [ $order_by_b, null, $order_by_b ],
            [ $order_by_a, $order_by_b, $order_by_a ],
            [ $order_by_b, $order_by_a, $order_by_b ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetOrderBy
     *
     * @param OrderBy $expected 期待値
     * @param mixed $prop_value プロパティ order_by の値
     * @param OrderBy $order_by メソッド setOrderBy の引数 order_by に渡す値
     */
    public function testSetOrderBy($expected, $prop_value, $order_by)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_ORDER_BY);
        $reflector->setValue($object, $prop_value);

        self::assertInstanceOf(Select::class, $object->setOrderBy($order_by));
        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        $from_origin = new From(new Table('table_name'));
        $from_alias_a = new From(new Alias('table_a', 'a'));
        $from_alias_b = new From(new Alias('table_b', 'b'));
        $columns_abc = new Columns([ 'a', 'b', 'c' ]);
        $columns_xyz = new Columns([ 'x', 'y', 'z' ]);
        $join_cond = (new Conditions())->addCondition(new Expression('a.x', 'b.x'));
        $join_b = new Join($from_alias_b->getDataSource(), Join::INNER_JOIN, $join_cond);
        $join_a = new Join($from_alias_a->getDataSource(), Join::INNER_JOIN, $join_cond);
        $where_abc = (new Conditions())->setConditions(
            new Expression('a', 0, Expression::SIGN_GT),
            new Expression('b', 10, Expression::SIGN_OU)
        );
        $where_xyz = (new Conditions())->setConditions(
            new Expression('x', 10, Expression::SIGN_AO),
            new Expression('z', 10, Expression::SIGN_LT)
        );
        $group_by_abc = new GroupBy(
            $columns_abc,
            (new Conditions())->addCondition(new Expression('c', 0, Expression::SIGN_GT))
        );
        $group_by_xyz = new GroupBy(
            $columns_xyz,
            (new Conditions())->addCondition(new Expression('z', 0, Expression::SIGN_GT))
        );
        $order_by_abc = new OrderBy(new Columns([ new Order('a'), new Order('b', Order::DESCENDING) ]));
        $order_by_xyz = new OrderBy(new Columns([ new Order('x', Order::DESCENDING), new Order('z') ]));

        return [
            [ 'SELECT * FROM table_name', $from_origin, null, null, null, null, null ],
            [ 'SELECT x, y, z FROM table_b AS b', $from_alias_b, $columns_xyz, null, null, null, null ],
            // JOIN のみ
            [
                'SELECT x, y, z FROM table_a AS a INNER JOIN table_b AS b ON a.x = b.x',
                $from_alias_a,
                $columns_xyz,
                $join_b,
                null,
                null,
                null,
            ],
            // WHERE のみ
            [
                'SELECT a, b, c FROM table_b AS b WHERE a > 0 AND b <= 10',
                $from_alias_b,
                $columns_abc,
                null,
                $where_abc,
                null,
                null,
            ],
            // GROUP BY のみ
            [
                'SELECT x, y, z FROM table_a AS a GROUP BY x, y, z HAVING z > 0',
                $from_alias_a,
                $columns_xyz,
                null,
                null,
                $group_by_xyz,
                null,
            ],
            // ORDER BY のみ
            [
                'SELECT a, b, c FROM table_b AS b ORDER BY a ASC, b DESC',
                $from_alias_b,
                $columns_abc,
                null,
                null,
                null,
                $order_by_abc,
            ],
            // JOIN + WHERE
            [
                'SELECT a, b, c FROM table_b AS b INNER JOIN table_a AS a ON a.x = b.x WHERE a > 0 AND b <= 10',
                $from_alias_b,
                $columns_abc,
                $join_a,
                $where_abc,
                null,
                null,
            ],
            // JOIN + GROUP BY
            [
                'SELECT a, b, c FROM table_b AS b INNER JOIN table_a AS a ON a.x = b.x GROUP BY a, b, c HAVING c > 0',
                $from_alias_b,
                $columns_abc,
                $join_a,
                null,
                $group_by_abc,
                null,
            ],
            // JOIN + ORDER BY
            [
                'SELECT x, y, z FROM table_a AS a INNER JOIN table_b AS b ON a.x = b.x ORDER BY x DESC, z ASC',
                $from_alias_a,
                $columns_xyz,
                $join_b,
                null,
                null,
                $order_by_xyz,
            ],
            // WHERE + GROUP BY
            [
                'SELECT x, y, z FROM table_a AS a WHERE x >= 10 AND z < 10 GROUP BY x, y, z HAVING z > 0',
                $from_alias_a,
                $columns_xyz,
                null,
                $where_xyz,
                $group_by_xyz,
                null,
            ],
            // WHERE + ORDER BY
            [
                'SELECT x, y, z FROM table_b AS b WHERE x >= 10 AND z < 10 ORDER BY x DESC, z ASC',
                $from_alias_b,
                $columns_xyz,
                null,
                $where_xyz,
                null,
                $order_by_xyz,
            ],
            // GROUP BY + ORDER BY
            [
                'SELECT a, b, c FROM table_a AS a GROUP BY a, b, c HAVING c > 0 ORDER BY a ASC, b DESC',
                $from_alias_a,
                $columns_abc,
                null,
                null,
                $group_by_abc,
                $order_by_abc,
            ],
            // JOIN + WHERE + GROUP BY
            [
                'SELECT x, y, z FROM table_b AS b INNER JOIN table_a AS a ON a.x = b.x WHERE x >= 10 AND z < 10 GROUP BY a, b, c HAVING c > 0',
                $from_alias_b,
                $columns_xyz,
                $join_a,
                $where_xyz,
                $group_by_abc,
                null,
            ],
            // JOIN + WHERE + ORDER BY
            [
                'SELECT a, b, c FROM table_a AS a INNER JOIN table_b AS b ON a.x = b.x WHERE a > 0 AND b <= 10 ORDER BY a ASC, b DESC',
                $from_alias_a,
                $columns_abc,
                $join_b,
                $where_abc,
                null,
                $order_by_abc,
            ],
            // JOIN + GROUP BY + ORDER BY
            [
                'SELECT x, y, z FROM table_b AS b INNER JOIN table_a AS a ON a.x = b.x GROUP BY x, y, z HAVING z > 0 ORDER BY x DESC, z ASC',
                $from_alias_b,
                $columns_xyz,
                $join_a,
                null,
                $group_by_xyz,
                $order_by_xyz,
            ],
            // WHERE + GROUP BY + ORDER BY
            [
                'SELECT a, b, c FROM table_a AS a WHERE a > 0 AND b <= 10 GROUP BY a, b, c HAVING c > 0 ORDER BY a ASC, b DESC',
                $from_alias_a,
                $columns_abc,
                null,
                $where_abc,
                $group_by_abc,
                $order_by_abc,
            ],
            // JOIN + WHERE + GROUP BY + ORDER BY
            [
                'SELECT x, y, z FROM table_b AS b INNER JOIN table_a AS a ON a.x = b.x WHERE x >= 10 AND z < 10 GROUP BY x, y, z HAVING z > 0 ORDER BY x DESC, z ASC',
                $from_alias_b,
                $columns_xyz,
                $join_a,
                $where_xyz,
                $group_by_xyz,
                $order_by_xyz
            ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected 期待値
     * @param FROM $from       コンストラクタの引数 from に渡す値
     * @param mixed $columns   コンストラクタの引数 columns に渡す値
     * @param mixed $join      メソッド setJoin の引数 join に渡す値（null以外の時のみ）
     * @param mixed $where     メソッド setWhere の引数 condition に渡す値（null以外の時のみ）
     * @param mixed $group_by  メソッド setGroupBy の引数 group_by に渡す値（null以外の時のみ）
     * @param mixed $order_by  メソッド setOrderBy の引数 order_by に渡す値（null以外の時のみ）
     */
    public function testToString($expected, $from, $columns, $join, $where, $group_by, $order_by)
    {
        $select = new Select($from, $columns);
        isset($join) && $select->setJoin($join);
        isset($where) && $select->setWhere($where);
        isset($group_by) && $select->setGroupBy($group_by);
        isset($order_by) && $select->setOrderBy($order_by);

        self::assertSame($expected, (string)$select);
    }
}
