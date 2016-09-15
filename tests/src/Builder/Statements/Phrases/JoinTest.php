<?php
namespace Yukar\Sql\Tests\Builder\Statements\Phrases;

use Yukar\Sql\Builder\Objects\Conditions;
use Yukar\Sql\Builder\Objects\Table;
use Yukar\Sql\Builder\Operators\Alias;
use Yukar\Sql\Builder\Operators\AtCondition\Expression;
use Yukar\Sql\Builder\Statements\Dml\Select;
use Yukar\Sql\Builder\Statements\Phrases\From;
use Yukar\Sql\Builder\Statements\Phrases\Join;
use Yukar\Sql\Interfaces\Builder\Objects\ICondition;

/**
 * クラス Join の単体テスト
 *
 * @author hiroki sugawara
 */
class JoinTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_DATA_SOURCE = 'data_source';
    const PROP_NAME_JOIN_TYPE = 'join_type';
    const PROP_NAME_ON_CONDITION = 'on_condition';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Join コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Join
    {
        /** @var Join $instance */
        $instance = (new \ReflectionClass(Join::class))->newInstanceWithoutConstructor();

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
    public function testGetPhraseString()
    {
        self::assertSame('%s JOIN %s%s', $this->getNewInstance()->getPhraseString());
    }

    /**
     * メソッド testGetDataSource のデータプロバイダー
     *
     * @return array
     */
    public function providerGetDataSource()
    {
        $table_obj = new Table('table_name');
        $alias_obj = new Alias('table_name', 'alias_name');
        $query_alias = new Alias('(SELECT * FROM table_name)', 'alias_query');
        $select_query = new Alias(new Select(new From($table_obj)), 'alias_query');

        return [
            [ 'table_name', 'table_name' ],
            [ 'table_name', $table_obj ],
            [ 'table_name AS alias_name', $alias_obj ],
            [ '(SELECT * FROM table_name) AS alias_query', $query_alias ],
            [ '(SELECT * FROM table_name) AS alias_query', $select_query ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetDataSource
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ data_source の値
     */
    public function testGetDataSource($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_DATA_SOURCE)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getDataSource());
    }

    /**
     * メソッド testSetDataSource のデータプロバイダー
     *
     * @return array
     */
    public function providerSetDataSource()
    {
        $table_obj = new Table('table_name');
        $alias_obj = new Alias('table_name', 'alias_name');
        $query_alias = new Alias('(SELECT * FROM table_name)', 'alias_query');
        $select_query = new Alias(new Select(new From($table_obj)), 'alias_query');

        return [
            [ 'table_name', null, 'table_name' ],
            [ $table_obj, null, $table_obj ],
            [ $alias_obj, null, $alias_obj ],
            [ $query_alias, null, $query_alias ],
            [ $select_query, null, $select_query ],
            [ 'table_name', $alias_obj, 'table_name' ],
            [ $table_obj, $alias_obj, $table_obj ],
            [ $alias_obj, $select_query, $alias_obj ],
            [ $query_alias, $table_obj, $query_alias ],
            [ $select_query, 'table_name', $select_query ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetDataSource
     *
     * @param string $expected    期待値
     * @param mixed $prop_value   プロパティ data_source の値
     * @param mixed $table_source メソッド setDataSource の引数 data_source に渡す値
     */
    public function testSetDataSource($expected, $prop_value, $table_source)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_DATA_SOURCE);
        $reflector->setValue($object, $prop_value);
        $object->setDataSource($table_source);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetTableSourceFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetDataSourceFailure()
    {
        return [
            [ \InvalidArgumentException::class, null, 0 ],
            [ \InvalidArgumentException::class, null, 10 ],
            [ \InvalidArgumentException::class, null, 1.5 ],
            [ \InvalidArgumentException::class, null, '' ],
            [ \InvalidArgumentException::class, null, true ],
            [ \InvalidArgumentException::class, null, null ],
            [ \InvalidArgumentException::class, null, [] ],
            [ \InvalidArgumentException::class, null, new \stdClass() ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetDataSourceFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ data_source の値
     * @param mixed $table_source  メソッド setDataSource の引数 data_source に渡す値
     */
    public function testSetDataSourceFailure($expected, $prop_value, $table_source)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_DATA_SOURCE)->setValue($object, $prop_value);
        $object->setDataSource($table_source);
    }

    /**
     * メソッド testGetJoinType のデータプロバイダー
     *
     * @return array
     */
    public function providerGetJoinType()
    {
        return [
            [ 'INNER', Join::JOIN_TYPE[Join::INNER_JOIN] ],
            [ 'LEFT', Join::JOIN_TYPE[Join::LEFT_JOIN] ],
            [ 'RIGHT', Join::JOIN_TYPE[Join::RIGHT_JOIN] ],
            [ 'CROSS', Join::JOIN_TYPE[Join::CROSS_JOIN] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetJoinType
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ join_type の値
     */
    public function testGetJoinType($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_JOIN_TYPE)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getJoinType());
    }

    /**
     * メソッド testSetJoinType のデータプロバイダー
     *
     * @return array
     */
    public function providerSetJoinType()
    {
        return [
            [ 'INNER', null, Join::INNER_JOIN ],
            [ 'LEFT', null, Join::LEFT_JOIN ],
            [ 'RIGHT', null, Join::RIGHT_JOIN ],
            [ 'CROSS', null, Join::CROSS_JOIN ],
            [ 'INNER', null, 0 ],
            [ 'INNER', null, 10 ],
            [ 'INNER', Join::CROSS_JOIN, Join::INNER_JOIN ],
            [ 'LEFT', Join::INNER_JOIN, Join::LEFT_JOIN ],
            [ 'RIGHT', Join::LEFT_JOIN, Join::RIGHT_JOIN ],
            [ 'CROSS', Join::RIGHT_JOIN, Join::CROSS_JOIN ],
            [ 'INNER', Join::LEFT_JOIN, 0 ],
            [ 'INNER', Join::RIGHT_JOIN, 10 ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetJoinType
     *
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ join_type の値
     * @param int $join_type    メソッド setJoinType の引数 join_type に渡す値
     */
    public function testSetJoinType($expected, $prop_value, $join_type)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_JOIN_TYPE);
        $reflector->setValue($object, $prop_value);
        $object->setJoinType($join_type);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testGetOnCondition のデータプロバイダー
     *
     * @return array
     */
    public function providerGetOnCondition()
    {
        $on_condition = (new Conditions())->setConditions(new Expression('a', 1), new Expression('b', 2));

        return [
            [ null, null ],
            [ $on_condition, $on_condition ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetOnCondition
     *
     * @param mixed $expected   期待値
     * @param mixed $prop_value プロパティ on_condition の値
     */
    public function testGetOnCondition($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_ON_CONDITION)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getOnCondition());
    }

    /**
     * メソッド testSetOnCondition のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOnCondition()
    {
        $base_condition = (new Conditions())->addCondition('x = 1');
        $expression_a = new Expression('a', 1);
        $expression_b = new Expression('b', 2);
        $expression_o = new Expression('o', 1);

        return [
            [ [ $expression_o ], null, (new Conditions())->addCondition($expression_o) ],
            [
                [ $expression_a, $expression_b ],
                null,
                (new Conditions())->setConditions($expression_a, $expression_b)
            ],
            [ [ $expression_o ], $base_condition, (new Conditions())->addCondition($expression_o) ],
            [
                [ $expression_a, $expression_b ],
                $base_condition,
                (new Conditions())->setConditions($expression_a, $expression_b)
            ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetOnCondition
     *
     * @param array $expected          期待値
     * @param mixed $prop_value        プロパティ on_condition の値
     * @param ICondition $on_condition メソッド setOnCondition の引数 on_condition に渡す値
     */
    public function testSetOnCondition($expected, $prop_value, $on_condition)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_ON_CONDITION);
        $reflector->setValue($object, $prop_value);
        $object->setOnCondition($on_condition);

        self::assertSame($expected, $reflector->getValue($object)->getConditions());
    }

    /**
     * メソッド testSetOnConditionFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOnConditionFailure()
    {
        return [
            [ \InvalidArgumentException::class, null, new Conditions() ],
            [
                \InvalidArgumentException::class,
                (new Conditions())->addCondition(new Expression('a', 1)),
                new Conditions()
            ],
            [
                \InvalidArgumentException::class,
                (new Conditions())->setConditions(new Expression('a', 1), new Expression('b', 1)),
                new Conditions()
            ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetOnConditionFailure
     *
     * @param \Exception $expected     期待値
     * @param mixed $prop_value        プロパティ on_condition の値
     * @param ICondition $on_condition メソッド setOnCondition の引数 on_condition に渡す値
     */
    public function testSetOnConditionFailure($expected, $prop_value, $on_condition)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_ON_CONDITION)->setValue($object, $prop_value);
        $object->setOnCondition($on_condition);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        $table_name = new Table('table_name');
        $alias_name = new Alias($table_name, 'a');
        $select_query = new Alias(new Select(new From($table_name)), 'a');
        $alias_text = 'table_name AS a';
        $alias_query = '(SELECT * FROM table_name) AS a';
        $on_orig_table = (new Conditions())->addCondition(new Expression('origin.a', 'table_name.a'));
        $on_as_table = (new Conditions())->addCondition(new Expression('origin.x', 'a.x'));

        return [
            // 内部結合
            [ 'INNER JOIN table_name', 'table_name', Join::INNER_JOIN, null ],
            [ 'INNER JOIN table_name ON origin.a = table_name.a', 'table_name', Join::INNER_JOIN, $on_orig_table ],
            [ 'INNER JOIN table_name', $table_name, Join::INNER_JOIN, null ],
            [ 'INNER JOIN table_name ON origin.a = table_name.a', $table_name, Join::INNER_JOIN, $on_orig_table ],
            [ 'INNER JOIN table_name AS a', $alias_text, Join::INNER_JOIN, null ],
            [ 'INNER JOIN table_name AS a ON origin.x = a.x', $alias_text, Join::INNER_JOIN, $on_as_table ],
            [ 'INNER JOIN table_name AS a', $alias_name, Join::INNER_JOIN, null ],
            [ 'INNER JOIN table_name AS a ON origin.x = a.x', $alias_name, Join::INNER_JOIN, $on_as_table ],
            [ 'INNER JOIN (SELECT * FROM table_name) AS a', $alias_query, Join::INNER_JOIN, null ],
            [
                'INNER JOIN (SELECT * FROM table_name) AS a ON origin.x = a.x',
                $alias_query,
                Join::INNER_JOIN,
                $on_as_table
            ],
            [ 'INNER JOIN (SELECT * FROM table_name) AS a', $select_query, Join::INNER_JOIN, null ],
            [
                'INNER JOIN (SELECT * FROM table_name) AS a ON origin.x = a.x',
                $select_query,
                Join::INNER_JOIN,
                $on_as_table
            ],
            // 外部結合（左）
            [ 'LEFT JOIN table_name', 'table_name', Join::LEFT_JOIN, null ],
            [ 'LEFT JOIN table_name ON origin.a = table_name.a', 'table_name', Join::LEFT_JOIN, $on_orig_table ],
            [ 'LEFT JOIN table_name', $table_name, Join::LEFT_JOIN, null ],
            [ 'LEFT JOIN table_name ON origin.a = table_name.a', $table_name, Join::LEFT_JOIN, $on_orig_table ],
            [ 'LEFT JOIN table_name AS a', $alias_text, Join::LEFT_JOIN, null ],
            [ 'LEFT JOIN table_name AS a ON origin.x = a.x', $alias_text, Join::LEFT_JOIN, $on_as_table ],
            [ 'LEFT JOIN table_name AS a', $alias_name, Join::LEFT_JOIN, null ],
            [ 'LEFT JOIN table_name AS a ON origin.x = a.x', $alias_name, Join::LEFT_JOIN, $on_as_table ],
            [ 'LEFT JOIN (SELECT * FROM table_name) AS a', $alias_query, Join::LEFT_JOIN, null ],
            [
                'LEFT JOIN (SELECT * FROM table_name) AS a ON origin.x = a.x',
                $alias_query,
                Join::LEFT_JOIN,
                $on_as_table
            ],
            [ 'LEFT JOIN (SELECT * FROM table_name) AS a', $select_query, Join::LEFT_JOIN, null ],
            [
                'LEFT JOIN (SELECT * FROM table_name) AS a ON origin.x = a.x',
                $select_query,
                Join::LEFT_JOIN,
                $on_as_table
            ],
            // 外部結合（右）
            [ 'RIGHT JOIN table_name', 'table_name', Join::RIGHT_JOIN, null ],
            [ 'RIGHT JOIN table_name ON origin.a = table_name.a', 'table_name', Join::RIGHT_JOIN, $on_orig_table ],
            [ 'RIGHT JOIN table_name', $table_name, Join::RIGHT_JOIN, null ],
            [ 'RIGHT JOIN table_name ON origin.a = table_name.a', $table_name, Join::RIGHT_JOIN, $on_orig_table ],
            [ 'RIGHT JOIN table_name AS a', $alias_text, Join::RIGHT_JOIN, null ],
            [ 'RIGHT JOIN table_name AS a ON origin.x = a.x', $alias_text, Join::RIGHT_JOIN, $on_as_table ],
            [ 'RIGHT JOIN table_name AS a', $alias_name, Join::RIGHT_JOIN, null ],
            [ 'RIGHT JOIN table_name AS a ON origin.x = a.x', $alias_name, Join::RIGHT_JOIN, $on_as_table ],
            [ 'RIGHT JOIN (SELECT * FROM table_name) AS a', $alias_query, Join::RIGHT_JOIN, null ],
            [
                'RIGHT JOIN (SELECT * FROM table_name) AS a ON origin.x = a.x',
                $alias_query,
                Join::RIGHT_JOIN,
                $on_as_table
            ],
            [ 'RIGHT JOIN (SELECT * FROM table_name) AS a', $select_query, Join::RIGHT_JOIN, null ],
            [
                'RIGHT JOIN (SELECT * FROM table_name) AS a ON origin.x = a.x',
                $select_query,
                Join::RIGHT_JOIN,
                $on_as_table
            ],
            // 交差結合
            [ 'CROSS JOIN table_name', 'table_name', Join::CROSS_JOIN, null ],
            [ 'CROSS JOIN table_name', $table_name, Join::CROSS_JOIN, null ],
            [ 'CROSS JOIN table_name AS a', $alias_text, Join::CROSS_JOIN, null ],
            [ 'CROSS JOIN table_name AS a', $alias_name, Join::CROSS_JOIN, null ],
            [ 'CROSS JOIN (SELECT * FROM table_name) AS a', $alias_query, Join::CROSS_JOIN, null ],
            [ 'CROSS JOIN (SELECT * FROM table_name) AS a', $select_query, Join::CROSS_JOIN, null ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected         期待値
     * @param mixed $table_source      コンストラクタの引数 table_source に渡す値
     * @param int $join_type           コンストラクタの引数 join_type に渡す値
     * @param ICondition $on_condition コンストラクタの引数 on_condition に渡す値
     */
    public function testToString($expected, $table_source, $join_type, $on_condition)
    {
        self::assertSame($expected, (string)new Join($table_source, $join_type, $on_condition));
    }
}
