<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Phrases;

use Yukar\Sql\Builder\Common\Objects\Columns;
use Yukar\Sql\Builder\Common\Objects\Conditions;
use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Operators\Alias;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Expression;
use Yukar\Sql\Builder\Common\Statements\Dml\Select;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Builder\Common\Statements\Phrases\Into;
use Yukar\Sql\Builder\Common\Statements\Phrases\Join;
use Yukar\Sql\Interfaces\Builder\Common\Objects\IDataSource;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス From の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Statements\Phrases
 * @author hiroki sugawara
 */
class FromTest extends CustomizedTestCase
{
    private const PROP_NAME_QUERY_SOURCE_LIST = 'query_source_list';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return From::class;
    }

    /**
     * 正常系テスト
     */
    public function testGetPhraseString(): void
    {
        $this->assertSame('FROM %s', $this->getNewInstance()->getPhraseString());
    }

    /**
     * メソッド testGetQuerySourceList のデータプロバイダー
     *
     * @return array
     */
    public function providerGetQuerySourceList(): array
    {
        $table_name = new Table('table_name');
        $sub_query_text = new Alias('(SELECT foo FROM table)', 'bar');
        $sub_query_obj = new Alias(new Select(new From(new Table('table')), new Columns([ 'foo' ])), 'bar');

        return [
            [ [ $table_name ], [ $table_name ] ],
            [ [ $sub_query_text ], [ $sub_query_text ] ],
            [ [ $sub_query_obj ], [ $sub_query_obj ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetQuerySourceList
     *
     * @param array            $expected   期待値
     * @param IDataSource|Join $prop_value プロパティ query_source_list の値
     */
    public function testGetQuerySourceList($expected, $prop_value): void
    {
        /** @var From $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_QUERY_SOURCE_LIST)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getQuerySourceList());
    }

    /**
     * メソッド testSetQuerySourceList のデータプロバイダー
     *
     * @return array
     */
    public function providerSetQuerySourceList(): array
    {
        $table_name = new Table('foo');
        $sub_query_text = new Alias('(SELECT col FROM table)', 'bar');
        $sub_query_obj = new Alias(new Select(new From(new Table('table')), new Columns([ 'col' ])), 'bar');
        $inner_join = new Join(
            new Table('abc'),
            Join::INNER_JOIN,
            (new Conditions())->addCondition(new Expression('foo.x', 'abc.x'))
        );
        $table_alias_name = new Alias(new Table('foo'), 'foobar');

        return [
            [ [ $table_name ], null, [ $table_name ] ],
            [ [ $sub_query_text ], null, [ $sub_query_text ] ],
            [ [ $sub_query_obj ], null, [ $sub_query_obj ] ],
            [ [ $table_name, $inner_join ], null, [ $table_name, $inner_join ] ],
            [ [ $table_alias_name, $table_alias_name ], null, [ $table_alias_name, $table_alias_name ] ],
            [ [ $table_name ], [ $table_alias_name, $table_alias_name ], [ $table_name ] ],
            [ [ $sub_query_text ], [ $table_name ], [ $sub_query_text ] ],
            [ [ $sub_query_obj ], [ $sub_query_text ], [ $sub_query_obj ] ],
            [ [ $table_name, $inner_join ], [ $sub_query_obj ], [ $table_name, $inner_join ] ],
            [ [ $table_alias_name, $table_alias_name ], [ $inner_join ], [ $table_alias_name, $table_alias_name ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetQuerySourceList
     *
     * @param array            $expected          期待値
     * @param IDataSource|Join $prop_value        プロパティ query_source_list の値
     * @param array            $query_source_list メソッド setQuerySourceList の引数 query_source_list に渡す値
     */
    public function testSetQuerySourceList($expected, $prop_value, $query_source_list): void
    {
        /** @var From $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_QUERY_SOURCE_LIST);
        $reflector->setValue($object, $prop_value);
        $object->setQuerySourceList(...$query_source_list);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetQuerySourceListFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetQuerySourceListFailure(): array
    {
        $table_name = new Table('foo');
        $inner_join = new Join(
            new Table('abc'),
            Join::INNER_JOIN,
            (new Conditions())->addCondition(new Expression('foo.x', 'abc.x'))
        );
        $into_table_name = new Into(new Table('table_name'));

        return [
            [ \BadMethodCallException::class, null, [] ],
            [ \BadMethodCallException::class, null, [ $inner_join ] ],
            [ \BadMethodCallException::class, null, [ $inner_join, $into_table_name ] ],
            [ \InvalidArgumentException::class, null, [ $table_name, $into_table_name ] ],
            [ \InvalidArgumentException::class, null, [ $table_name, $into_table_name, $inner_join ] ],
            [ \TypeError::class, null, [ new \stdClass() ] ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetQuerySourceListFailure
     *
     * @param \Exception       $expected          期待値
     * @param IDataSource|Join $prop_value        プロパティ query_source_list の値
     * @param array            $query_source_list メソッド setQuerySourceList の引数 query_source_list に渡す値
     */
    public function testSetQuerySourceListFailure($expected, $prop_value, $query_source_list): void
    {
        $this->expectException($expected);

        /** @var From $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_QUERY_SOURCE_LIST)->setValue($object, $prop_value);
        $object->setQuerySourceList(...$query_source_list);
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
            [ 'FROM foo', [ $table_name ] ],
            [ 'FROM (SELECT col FROM foo) AS bar', [ $sub_query_text ] ],
            [ 'FROM (SELECT col FROM foo) AS bar', [ $sub_query_obj ] ],
            [ 'FROM foo INNER JOIN abc ON foo.x = abc.x', [ $table_name, $inner_join ] ],
            [ 'FROM (SELECT col FROM foo) AS bar LEFT JOIN abc ON bar.x = abc.x', [ $sub_query_text, $left_join ] ],
            [ 'FROM (SELECT col FROM foo) AS bar LEFT JOIN abc ON bar.x = abc.x', [ $sub_query_obj, $left_join ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected          期待値
     * @param array  $query_source_list コンストラクタの引数 data_source に渡す値
     */
    public function testToString($expected, $query_source_list): void
    {
        $this->assertSame($expected, (string)new From(...$query_source_list));
    }
}
