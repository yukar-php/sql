<?php
namespace Yukar\Sql\Tests\Builder\Operators\AtCondition;

use Yukar\Sql\Builder\Objects\Columns;
use Yukar\Sql\Builder\Objects\Table;
use Yukar\Sql\Builder\Operators\AtCondition\Exists;
use Yukar\Sql\Builder\Statements\Dml\Select;
use Yukar\Sql\Builder\Statements\Phrases\From;

/**
 * クラス Exists の単体テスト
 *
 * @author hiroki sugawara
 */
class ExistsTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_NEEDLE = 'needle';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Exists コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Exists
    {
        /** @var Exists $instance */
        $instance = (new \ReflectionClass(Exists::class))->newInstanceWithoutConstructor();

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
     * メソッド testGetNeedle のデータプロバイダー
     *
     * @return array
     */
    public function providerGetNeedle()
    {
        return [
            [ 'SELECT * FROM table_name', 'SELECT * FROM table_name' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetNeedle
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ needle の値
     */
    public function testGetNeedle($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_NEEDLE)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getNeedle());
    }

    /**
     * メソッド testSetNeedle のデータプロバイダー
     *
     * @return array
     */
    public function providerSetNeedle()
    {
        $table_name = new Table('table_name');
        $select_query_a = new Select(new From($table_name));
        $select_query_b = new Select(new From($table_name), new Columns([ 'col' ]));

        return [
            [ 'SELECT * FROM table_name', null, 'SELECT * FROM table_name' ],
            [ 'SELECT * FROM table_name', null, $select_query_a ],
            [ 'SELECT col FROM table_name', 'SELECT * FROM table_name', 'SELECT col FROM table_name' ],
            [ 'SELECT col FROM table_name', 'SELECT * FROM table_name', $select_query_b ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetNeedle
     *
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ needle の値
     * @param mixed $needle     メソッド setNeedle の引数 needle に渡す値
     */
    public function testSetNeedle($expected, $prop_value, $needle)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_NEEDLE);
        $reflector->setValue($object, $prop_value);
        $object->setNeedle($needle);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetNeedleFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetNeedleFailure()
    {
        return [
            [ \InvalidArgumentException::class, null, '' ],
            [ \InvalidArgumentException::class, null, '0' ],
            [ \InvalidArgumentException::class, null, 0 ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetNeedleFailure
     *
     * @param |Exception $expected 期待値
     * @param mixed $prop_value    プロパティ needle の値
     * @param mixed $needle        メソッド setNeedle の引数 needle に渡す値
     */
    public function testSetNeedleFailure($expected, $prop_value, $needle)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_NEEDLE)->setValue($object, $prop_value);
        $object->setNeedle($needle);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        $select = new Select(new From(new Table('table')), new Columns([ 'col' ]));

        return [
            [ 'column EXISTS (SELECT col FROM table)', 'column', 'SELECT col FROM table', false ],
            [ 'column EXISTS (SELECT col FROM table)', 'column', $select, false ],
            [ 'column NOT EXISTS (SELECT col FROM table)', 'column', 'SELECT col FROM table', true ],
            [ 'column NOT EXISTS (SELECT col FROM table)', 'column', $select, true ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected 期待値
     * @param string $name     コンストラクタの引数 name に渡す値
     * @param mixed $needle    コンストラクタの引数 needle に渡す値
     * @param bool $is_not     コンストラクタの引数 is_not に渡す値
     */
    public function testToString($expected, $name, $needle, $is_not)
    {
        self::assertSame($expected, (string)new Exists($name, $needle, $is_not));
    }
}
