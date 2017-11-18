<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Phrases;

use Yukar\Sql\Builder\Common\Objects\Columns;
use Yukar\Sql\Builder\Common\Objects\Conditions;
use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Operators\Alias;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Expression;
use Yukar\Sql\Builder\Common\Statements\Dml\Select;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Builder\Common\Statements\Phrases\Join;
use Yukar\Sql\Interfaces\Builder\Common\Objects\IDataSource;

/**
 * クラス From の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Statements\Phrases
 * @author hiroki sugawara
 */
class FromTest extends \PHPUnit_Framework_TestCase
{
    private const PROP_NAME_DATA_SOURCE = 'data_source';
    private const PROP_NAME_JOIN = 'join';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return From コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): From
    {
        /** @var From $instance */
        $instance = (new \ReflectionClass(From::class))->newInstanceWithoutConstructor();

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
    public function testGetPhraseString(): void
    {
        $this->assertSame('FROM %s %s', $this->getNewInstance()->getPhraseString());
    }

    /**
     * メソッド testGetDataSource のデータプロバイダー
     *
     * @return array
     */
    public function providerGetDataSource(): array
    {
        $table_name = new Table('table_name');
        $sub_query_text = new Alias('(SELECT foo FROM table)', 'bar');
        $sub_query_obj = new Alias(new Select(new From(new Table('table')), new Columns([ 'foo' ])), 'bar');

        return [
            [ $table_name, $table_name ],
            [ $sub_query_text, $sub_query_text ],
            [ $sub_query_obj, $sub_query_obj ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetDataSource
     *
     * @param string      $expected   期待値
     * @param IDataSource $prop_value プロパティ data_source の値
     */
    public function testGetDataSource($expected, $prop_value): void
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_DATA_SOURCE)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getDataSource());
    }

    /**
     * メソッド testSetDataSource のデータプロバイダー
     *
     * @return array
     */
    public function providerSetDataSource(): array
    {
        $table_name = new Table('table_name');
        $sub_query_text = new Alias('(SELECT foo FROM table)', 'bar');
        $sub_query_obj = new Alias(new Select(new From(new Table('table')), new Columns([ 'foo' ])), 'bar');

        return [
            [ $table_name, null, $table_name ],
            [ $sub_query_text, null, $sub_query_text ],
            [ $sub_query_obj, null, $sub_query_obj ],
            [ $table_name, $sub_query_obj, $table_name ],
            [ $sub_query_text, $table_name, $sub_query_text ],
            [ $sub_query_obj, $sub_query_text, $sub_query_obj ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetDataSource
     *
     * @param string      $expected    期待値
     * @param IDataSource $prop_value  プロパティ data_source の値
     * @param IDataSource $data_source メソッド setDataSource の引数 data_source に渡す値
     */
    public function testSetDataSource($expected, $prop_value, $data_source): void
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_DATA_SOURCE);
        $reflector->setValue($object, $prop_value);
        $object->setDataSource($data_source);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testGetJoin のデータプロバイダー
     *
     * @return array
     */
    public function providerGetJoin(): array
    {
        $join = (new Join(new Alias(new Table('table_name'), 'b')));
        $join->setOnCondition(
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
    public function testGetJoin($expected, $prop_value): void
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_JOIN)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getJoin());
    }

    /**
     * メソッド testSetJoin のデータプロバイダー
     *
     * @return array
     */
    public function providerSetJoin(): array
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
    public function testSetJoin($expected, $prop_value, $join): void
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_JOIN);
        $reflector->setValue($object, $prop_value);
        $object->setJoin($join);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        $table_name = new Table('foo');
        $sub_query_text = new Alias('(SELECT col FROM foo)', 'bar');
        $sub_query_obj = new Alias(new Select(new From(new Table('foo')), new Columns([ 'col' ])), 'bar');
        $base_table_name = new Table('abc');
        $inner_join = new Join(
            $base_table_name,
            Join::INNER_JOIN,
            (new Conditions())->addCondition(new Expression('foo.x', 'abc.x'))
        );
        $left_join = new Join(
            $base_table_name,
            Join::LEFT_JOIN,
            (new Conditions())->addCondition(new Expression('bar.x', 'abc.x'))
        );

        return [
            [ 'FROM foo', $table_name, null ],
            [ 'FROM (SELECT col FROM foo) AS bar', $sub_query_text, null ],
            [ 'FROM (SELECT col FROM foo) AS bar', $sub_query_obj, null ],
            [ 'FROM foo INNER JOIN abc ON foo.x = abc.x', $table_name, $inner_join ],
            [ 'FROM (SELECT col FROM foo) AS bar LEFT JOIN abc ON bar.x = abc.x', $sub_query_text, $left_join ],
            [ 'FROM (SELECT col FROM foo) AS bar LEFT JOIN abc ON bar.x = abc.x', $sub_query_obj, $left_join ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string      $expected    期待値
     * @param IDataSource $data_source コンストラクタの引数 data_source に渡す値
     * @param Join        $join        コンストラクタの引数 join に渡す値
     */
    public function testToString($expected, $data_source, $join): void
    {
        $this->assertSame($expected, (string)new From($data_source, $join));
    }
}
