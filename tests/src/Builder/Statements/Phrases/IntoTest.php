<?php
namespace Yukar\Sql\Tests\Builder\Statements\Phrases;

use Yukar\Sql\Builder\Objects\Columns;
use Yukar\Sql\Builder\Objects\Table;
use Yukar\Sql\Builder\Statements\Phrases\Into;
use Yukar\Sql\Interfaces\Builder\Objects\IColumns;
use Yukar\Sql\Interfaces\Builder\Objects\ITable;

/**
 * クラス Into の単体テスト
 *
 * @author hiroki sugawara
 */
class IntoTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_DATA_SOURCE = 'data_source';
    const PROP_NAME_COLUMNS = 'columns';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Into コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Into
    {
        /** @var Into $instance */
        $instance = (new \ReflectionClass(Into::class))->newInstanceWithoutConstructor();

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
        self::assertSame('INTO %s %s', $this->getNewInstance()->getPhraseString());
    }

    /**
     * メソッド testGetDataSource のデータプロバイダー
     *
     * @return array
     */
    public function providerGetDataSource()
    {
        $table_name = new Table('table_name');

        return [
            [ $table_name, $table_name ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetDataSource
     *
     * @param ITable $expected   期待値
     * @param ITable $prop_value プロパティ data_source の値
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
        $table_name_a = new Table('table_name_a');
        $table_name_b = new Table('table_name_b');

        return [
            [ $table_name_a, null, $table_name_a ],
            [ $table_name_b, $table_name_a, $table_name_b ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetDataSource
     *
     * @param ITable $expected    期待値
     * @param mixed $prop_value   プロパティ data_source の値
     * @param ITable $data_source メソッド setDataSource の引数 data_source に渡す値
     */
    public function testSetDataSource($expected, $prop_value, $data_source)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_DATA_SOURCE);
        $reflector->setValue($object, $prop_value);
        $object->setDataSource($data_source);

        self::assertSame($expected, $reflector->getValue($object));
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
            [ null, null ],
            [ $columns, $columns ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetColumns
     *
     * @param IColumns|null $expected   期待値
     * @param IColumns|null $prop_value プロパティ columns の値
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
        $columns = new Columns([ 'a', 'b', 'c' ]);

        return [
            [ $columns, null, $columns ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetColumns
     *
     * @param IColumns|null $expected 期待値
     * @param mixed $prop_value       プロパティ columns の値
     * @param IColumns|null $columns  メソッド setColumns の引数 columns に渡す値
     */
    public function testSetColumns($expected, $prop_value, $columns)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_COLUMNS);
        $reflector->setValue($object, $prop_value);
        $object->setColumns($columns);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        $table_name = new Table('table_name');
        $columns = new Columns([ 'a', 'b', 'c' ]);

        return [
            [ 'INTO table_name', $table_name, null ],
            [ 'INTO table_name (a, b, c)', $table_name, $columns ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected    期待値
     * @param ITable $data_source コンストラクタ data_source に渡す値
     * @param IColumns $columns   コンストラクタ columns に渡す値
     */
    public function testToString($expected, $data_source, $columns)
    {
        self::assertSame($expected, (string)new Into($data_source, $columns));
    }
}
