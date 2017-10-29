<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Dml;

use Yukar\Sql\Builder\Common\Objects\Columns;
use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Operators\Alias;
use Yukar\Sql\Builder\Common\Statements\Dml\Insert;
use Yukar\Sql\Builder\Common\Statements\Dml\Select;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Builder\Common\Statements\Phrases\Into;
use Yukar\Sql\Builder\Common\Statements\Phrases\Values;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ISqlQuerySource;
use Yukar\Sql\Interfaces\Builder\Common\Statements\ISelectQuery;

/**
 * クラス Insert の単体テスト
 *
 * @author hiroki sugawara
 */
class InsertTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_VALUES = 'values';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Insert コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Insert
    {
        /** @var Insert $instance */
        $instance = (new \ReflectionClass(Insert::class))->newInstanceWithoutConstructor();

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
        self::assertSame('INSERT %s %s', $this->getNewInstance()->getQueryFormat());
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
            [ new From(new Table('table_name')) ],
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
     * メソッド testGetValues のデータプロバイダー
     *
     * @return array
     */
    public function providerGetValues()
    {
        $values = new Values([ [ 1, 2, 3 ], [ 4, 5, 6 ] ]);
        $select = new Select(new From(new Table('table_name')));

        return [
            [ $values, $values ],
            [ $select, $select ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetValues
     *
     * @param Values|ISelectQuery $expected   期待値
     * @param Values|ISelectQuery $prop_value プロパティ values の値
     */
    public function testGetValues($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_VALUES)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getValues());
    }

    /**
     * メソッド testSetValues のデータプロバイダー
     *
     * @return array
     */
    public function providerSetValues()
    {
        $values = new Values([ [ 1, 2, 3 ], [ 4, 5, 6 ] ]);
        $select = new Select(new From(new Table('table_name')));

        return [
            [ $values, null, $values ],
            [ $select, null, $select ],
            [ $values, $select, $values ],
            [ $select, $values, $select ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetValues
     *
     * @param Values|ISelectQuery $expected 期待値
     * @param mixed $prop_value             プロパティ values の値
     * @param Values|ISelectQuery $values   メソッド setValues の引数 values に渡す値
     */
    public function testSetValues($expected, $prop_value, $values)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_VALUES);
        $reflector->setValue($object, $prop_value);
        $object->setValues($values);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetValuesFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetValuesFailure()
    {
        $values = new Values([ [ 1, 2, 3 ], [ 4, 5, 6 ] ]);

        return [
            // 初回設定時
            [ \InvalidArgumentException::class, null, null ],
            [ \InvalidArgumentException::class, null, 0 ],
            [ \InvalidArgumentException::class, null, 0.5 ],
            [ \InvalidArgumentException::class, null, '2' ],
            [ \InvalidArgumentException::class, null, '1.1' ],
            [ \InvalidArgumentException::class, null, true ],
            [ \InvalidArgumentException::class, null, [] ],
            [ \InvalidArgumentException::class, null, new \stdClass() ],
            [ \InvalidArgumentException::class, null, new \ArrayObject() ],
            // 二回目以降の設定時
            [ \InvalidArgumentException::class, $values, null ],
            [ \InvalidArgumentException::class, $values, 1 ],
            [ \InvalidArgumentException::class, $values, 1.5 ],
            [ \InvalidArgumentException::class, $values, 'abc' ],
            [ \InvalidArgumentException::class, $values, '100' ],
            [ \InvalidArgumentException::class, $values, false ],
            [ \InvalidArgumentException::class, $values, [] ],
            [ \InvalidArgumentException::class, $values, new \stdClass() ],
            [ \InvalidArgumentException::class, $values, new \ArrayObject() ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetValuesFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ values の値
     * @param mixed $values        メソッド setValues の引数 values に渡す値
     */
    public function testSetValuesFailure($expected, $prop_value, $values)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_VALUES)->setValue($object, $prop_value);
        $object->setValues($values);
    }


    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        $into_table_name = new Into(new Table('table_name'));
        $into_table_columns = new Into(new Table('table_name'), new Columns([ 'a', 'b', 'c' ]));
        $values = new Values([ [ 1, 2, 3 ], [ 'x', 'y', 'z' ] ]);
        $select = new Select(new From(new Table('select_table')));

        return [
            [ "INSERT INTO table_name VALUES (1, 2, 3), ('x', 'y', 'z')", $into_table_name, $values ],
            [ "INSERT INTO table_name (a, b, c) VALUES (1, 2, 3), ('x', 'y', 'z')", $into_table_columns, $values ],
            [ 'INSERT INTO table_name SELECT * FROM select_table', $into_table_name, $select ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected            期待値
     * @param Into $into                  コンストラクタの引数 into に渡す値
     * @param Values|ISelectQuery $values コンストラクタの引数 values に渡す値
     */
    public function testToString($expected, $into, $values)
    {
        self::assertSame($expected, (string)new Insert($into, $values));
    }
}
