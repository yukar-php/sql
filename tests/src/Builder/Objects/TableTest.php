<?php
namespace Yukar\Sql\Tests\Builder\Objects;

use Yukar\Sql\Builder\Objects\Columns;
use Yukar\Sql\Builder\Objects\Table;

/**
 * クラス Table の単体テスト
 *
 * @author hiroki sugawara
 */
class TableTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_TABLE_NAME = 'table_name';
    const PROP_NAME_COLUMN_LIST = 'column_list';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return object コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getTableInstance()
    {
        return (new \ReflectionClass(Table::class))->newInstanceWithoutConstructor();
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
    public function providerGetTableName()
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
    public function testGetTableName($expected, $table_name)
    {
        $object = $this->getTableInstance();
        $this->getProperty($object, self::PROP_NAME_TABLE_NAME)->setValue($object, $table_name);

        $this->assertSame($expected, $object->getTableName());
    }

    /**
     * メソッド testSetTableName のデータプロバイダー
     *
     * @return array
     */
    public function providerSetTableName()
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
    public function testSetTableName($expected, $prop_value, $table_name)
    {
        $object = $this->getTableInstance();
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
    public function providerSetTableNameFailure()
    {
        return [
            [ '\InvalidArgumentException', null, '' ],
            [ '\InvalidArgumentException', 'TableName', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetTableNameFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ table_name の値
     * @param mixed $table_name    メソッド setTableName の引数 name に渡す値
     */
    public function testSetTableNameFailure($expected, $prop_value, $table_name)
    {
        $this->expectException($expected);

        $object = $this->getTableInstance();
        $this->getProperty($object, self::PROP_NAME_TABLE_NAME)->setValue($object, $prop_value);
        $object->setTableName($table_name);
    }

    /**
     * メソッド testGetDefinedColumns のデータプロバイダー
     *
     * @return array
     */
    public function providerGetDefinedColumns()
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
    public function testGetDefinedColumns($expected, $prop_value)
    {
        $object = $this->getTableInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN_LIST)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getDefinedColumns());
    }

    /**
     * メソッド testGetDefinedColumnsFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerGetDefinedColumnsFailure()
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
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ column_list の値
     */
    public function testGetDefinedColumnsFailure($expected, $prop_value)
    {
        $this->expectException($expected);

        $object = $this->getTableInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN_LIST)->setValue($object, $prop_value);
        $object->getDefinedColumns();
    }

    /**
     * メソッド testSetDefinedColumns のデータプロバイダー
     *
     * @return array
     */
    public function providerSetDefinedColumns()
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
     * @param array $expected   期待値
     * @param array $prop_value プロパティ column_list の値
     * @param Columns $columns  メソッド setDefinedColumns の引数 columns に渡す値
     */
    public function testSetDefinedColumns($expected, $prop_value, $columns)
    {
        $object = $this->getTableInstance();
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
    public function providerSetDefinedColumnsFailure()
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
     * @param |Exception $expected 期待値
     * @param array $prop_value    プロパティ column_list の値
     * @param Columns $columns     メソッド setDefinedColumns の引数 columns に渡す値
     */
    public function testSetDefinedColumnsFailure($expected, $prop_value, $columns)
    {
        $this->expectException($expected);

        $object = $this->getTableInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN_LIST)->setValue($object, $prop_value);
        $object->setDefinedColumns($columns);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        return [
            [ 'TestTable', 'TestTable', new Columns([ 'test1', 'test2' ]) ],
            [ 'schema.TestTable', 'schema.TestTable', new Columns([ 'test1', 'test2' ]) ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected   期待値
     * @param string $table_name コンストラクタの引数 name に渡す値
     * @param Columns $columns   メソッド setDefinedColumns の引数 columns に渡す値
     */
    public function testToString($expected, $table_name, $columns)
    {
        $this->assertSame($expected, (string)(new Table($table_name))->setDefinedColumns($columns));
    }
}
