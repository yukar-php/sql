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
        $this->getTableNameProperty(new \ReflectionClass($object))->setValue($object, $table_name);

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
        $reflector = $this->getTableNameProperty(new \ReflectionClass($object));
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
        $this->getTableNameProperty(new \ReflectionClass($object))->setValue($object, $prop_value);
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
        $this->getColumnListProperty(new \ReflectionClass($object))->setValue($object, $prop_value);

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
        $this->getColumnListProperty(new \ReflectionClass($object))->setValue($object, $prop_value);
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
        $reflector = $this->getColumnListProperty(new \ReflectionClass($object));
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
        $this->getColumnListProperty(new \ReflectionClass($object))->setValue($object, $prop_value);
        $object->setDefinedColumns($columns);
    }

    /**
     * コンストラクタを通さずに作成した Table クラスの新しいインスタンスを取得します。
     *
     * @return Table コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getTableInstance(): Table
    {
        return (new \ReflectionClass('Yukar\Sql\Builder\Objects\Table'))->newInstanceWithoutConstructor();
    }

    /**
     * プロパティ table_name のリフレクションを持つインスタンスを取得します。
     *
     * @param \ReflectionClass $reflector クラス Table のオブジェクトのリフレクション
     *
     * @return \ReflectionProperty プロパティ table_name のリフレクションを持つインスタンス
     */
    private function getTableNameProperty(\ReflectionClass $reflector): \ReflectionProperty
    {
        $property = $reflector->getProperty('table_name');
        $property->setAccessible(true);

        return $property;
    }

    /**
     * プロパティ column_list のリフレクションを持つインスタンスを取得します。
     *
     * @param \ReflectionClass $reflector クラス Table のオブジェクトのリフレクション
     *
     * @return \ReflectionProperty プロパティ column_list のリフレクションを持つインスタンス
     */
    private function getColumnListProperty(\ReflectionClass $reflector): \ReflectionProperty
    {
        $property = $reflector->getProperty('column_list');
        $property->setAccessible(true);

        return $property;
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
