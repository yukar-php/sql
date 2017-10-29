<?php
namespace Yukar\Sql\Tests\Builder\Common\Objects;

use Yukar\Sql\Builder\Common\Objects\Columns;
use Yukar\Sql\Builder\Common\Objects\DelimitedIdentifier;
use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Interfaces\Builder\Common\Objects\IColumns;

/**
 * クラス Table の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Objects
 * @author hiroki sugawara
 */
class TableTest extends \PHPUnit_Framework_TestCase
{
    private const PROP_NAME_TABLE_NAME = 'table_name';
    private const PROP_NAME_COLUMN_LIST = 'column_list';

    /**
     * 単体テスト対象となるクラスのテストが全て終わった時に最後に実行します。
     */
    public static function tearDownAfterClass(): void
    {
        $object = new \ReflectionClass(DelimitedIdentifier::class);
        $property = $object->getProperty('quote_type');
        $property->setAccessible(true);
        $property->setValue($object, null);
    }

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Table コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Table
    {
        /** @var Table $instance */
        $instance = (new \ReflectionClass(Table::class))->newInstanceWithoutConstructor();

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
     * メソッド testGetTableName のデータプロバイダー
     *
     * @return array
     */
    public function providerGetTableName(): array
    {
        return [
            [ 'table', 'table' ],
            [ 'schema.table', 'schema.table' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetTableName
     *
     * @param string $expected   期待値
     * @param string $table_name プロパティ table_name の値
     */
    public function testGetTableName($expected, $table_name): void
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_TABLE_NAME)->setValue($object, $table_name);

        $this->assertSame($expected, $object->getTableName());
    }

    /**
     * メソッド testSetTableName のデータプロバイダー
     *
     * @return array
     */
    public function providerSetTableName(): array
    {
        return [
            [ 'setTable', null, 'setTable' ],
            [ 'setTable', 'baseTable', 'setTable' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetTableName
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ table_name の値
     * @param string $table_name メソッド setTableName の引数 name に渡す値
     */
    public function testSetTableName($expected, $prop_value, $table_name): void
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_TABLE_NAME);
        $reflector->setValue($object, $prop_value);
        $object->setTableName($table_name);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetTableNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetTableNameFailure(): array
    {
        return [
            [ \InvalidArgumentException::class, null, '' ],
            [ \InvalidArgumentException::class, 'TableName', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetTableNameFailure
     *
     * @param \Exception $expected   期待値
     * @param mixed      $prop_value プロパティ table_name の値
     * @param mixed      $table_name メソッド setTableName の引数 name に渡す値
     */
    public function testSetTableNameFailure($expected, $prop_value, $table_name): void
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_TABLE_NAME)->setValue($object, $prop_value);
        $object->setTableName($table_name);
    }

    /**
     * メソッド testGetDefinedColumns のデータプロバイダー
     *
     * @return array
     */
    public function providerGetDefinedColumns(): array
    {
        return [
            [ [ 'a', 'b', 'c' ], [ 'a', 'b', 'c' ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetDefinedColumns
     *
     * @param array $expected   期待値
     * @param array $prop_value プロパティ column_list の値
     */
    public function testGetDefinedColumns($expected, $prop_value): void
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN_LIST)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getDefinedColumns());
    }

    /**
     * メソッド testGetDefinedColumnsFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerGetDefinedColumnsFailure(): array
    {
        return [
            [ '\BadMethodCallException', [] ],
            [ '\BadMethodCallException', null ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerGetDefinedColumnsFailure
     *
     * @param \Exception $expected  期待値
     * @param mixed     $prop_value プロパティ column_list の値
     */
    public function testGetDefinedColumnsFailure($expected, $prop_value): void
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN_LIST)->setValue($object, $prop_value);
        $object->getDefinedColumns();
    }

    /**
     * メソッド testSetDefinedColumns のデータプロバイダー
     *
     * @return array
     */
    public function providerSetDefinedColumns(): array
    {
        return [
            [ [ 'a', 'b', 'c' ], [], new Columns([ 'a', 'b', 'c' ]) ],
            [ [ 'a', 'b', 'c' ], [ 'x', 'y', 'z' ], new Columns([ 'a', 'b', 'c' ]) ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetDefinedColumns
     *
     * @param array    $expected   期待値
     * @param array    $prop_value プロパティ column_list の値
     * @param IColumns $columns    メソッド setDefinedColumns の引数 columns に渡す値
     */
    public function testSetDefinedColumns($expected, $prop_value, $columns): void
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_COLUMN_LIST);
        $reflector->setValue($object, $prop_value);

        $this->assertInstanceOf(get_class($object), $object->setDefinedColumns($columns));
        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetDefinedColumnsFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetDefinedColumnsFailure(): array
    {
        return [
            [ '\InvalidArgumentException', [], new Columns([]) ],
            [ '\InvalidArgumentException', [ 'x', 'y', 'z' ], new Columns([]) ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetDefinedColumnsFailure
     *
     * @param |Exception $expected   期待値
     * @param array      $prop_value プロパティ column_list の値
     * @param IColumns   $columns    メソッド setDefinedColumns の引数 columns に渡す値
     */
    public function testSetDefinedColumnsFailure($expected, $prop_value, $columns): void
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN_LIST)->setValue($object, $prop_value);
        $object->setDefinedColumns($columns);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        $columns = new Columns([ 'test1', 'test2' ]);

        return [
            [ 'TestTable', 'TestTable', null, $columns ],
            [ '"TestTable"', 'TestTable', DelimitedIdentifier::ANSI_QUOTES_TYPE, $columns ],
            [ '`TestTable`', 'TestTable', DelimitedIdentifier::MYSQL_QUOTES_TYPE, $columns ],
            [ '[TestTable]', 'TestTable', DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE, $columns ],
            [ 'schema.TestTable', 'schema.TestTable', null, $columns ],
            [ '"schema"."TestTable"', 'schema.TestTable', DelimitedIdentifier::ANSI_QUOTES_TYPE, $columns ],
            [ '`schema`.`TestTable`', 'schema.TestTable', DelimitedIdentifier::MYSQL_QUOTES_TYPE, $columns ],
            [ '[schema].[TestTable]', 'schema.TestTable', DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE, $columns ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string   $expected   期待値
     * @param string   $table_name コンストラクタの引数 name に渡す値
     * @param int      $quote_type メソッド init の引数 quote_type に渡す値
     * @param IColumns $columns    メソッド setDefinedColumns の引数 columns に渡す値
     */
    public function testToString($expected, $table_name, $quote_type, $columns)
    {
        DelimitedIdentifier::init($quote_type ?? DelimitedIdentifier::NONE_QUOTES_TYPE);

        $this->assertSame(
            $expected,
            (string)(new Table($table_name, DelimitedIdentifier::get()))->setDefinedColumns($columns)
        );
    }
}
