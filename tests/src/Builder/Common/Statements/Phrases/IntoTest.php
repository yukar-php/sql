<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Phrases;

use Yukar\Sql\Builder\Common\Objects\Columns;
use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Operators\Alias;
use Yukar\Sql\Builder\Common\Operators\Order;
use Yukar\Sql\Builder\Common\Statements\Phrases\Into;
use Yukar\Sql\Interfaces\Builder\Common\Objects\IColumns;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ITable;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス Into の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Statements\Phrases
 * @author hiroki sugawara
 */
class IntoTest extends CustomizedTestCase
{
    private const PROP_NAME_DATA_SOURCE = 'data_source';
    private const PROP_NAME_COLUMNS = 'columns';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return Into::class;
    }

    /**
     * 正常系テスト
     */
    public function testGetPhraseString(): void
    {
        $this->assertSame('INTO %s %s', $this->getNewInstance()->getPhraseString());
    }

    /**
     * メソッド testGetDataSource のデータプロバイダー
     *
     * @return array
     */
    public function providerGetDataSource(): array
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
    public function testGetDataSource($expected, $prop_value): void
    {
        /** @var Into $object */
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
     * @param mixed  $prop_value  プロパティ data_source の値
     * @param ITable $data_source メソッド setDataSource の引数 data_source に渡す値
     */
    public function testSetDataSource($expected, $prop_value, $data_source): void
    {
        /** @var Into $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_DATA_SOURCE);
        $reflector->setValue($object, $prop_value);
        $object->setDataSource($data_source);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testGetColumns のデータプロバイダー
     *
     * @return array
     */
    public function providerGetColumns(): array
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
    public function testGetColumns($expected, $prop_value): void
    {
        /** @var Into $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMNS)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getColumns());
    }

    /**
     * メソッド testSetColumns のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumns(): array
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
     * @param IColumns|null $expected   期待値
     * @param mixed         $prop_value プロパティ columns の値
     * @param IColumns|null $columns    メソッド setColumns の引数 columns に渡す値
     */
    public function testSetColumns($expected, $prop_value, $columns): void
    {
        /** @var Into $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_COLUMNS);
        $reflector->setValue($object, $prop_value);
        $object->setColumns($columns);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetColumnsFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumnsFailure(): array
    {
        $valid_columns = new Columns([ 'x', 'y', 'z' ]);
        $empty_columns = new Columns();
        $order_columns = new Columns([ new Order('col_name') ]);
        $alias_columns = new Columns([ new Alias('orig_table', 'as_table') ]);

        return [
            [ \InvalidArgumentException::class, null, $empty_columns ],
            [ \InvalidArgumentException::class, null, $order_columns ],
            [ \InvalidArgumentException::class, null, $alias_columns ],
            [ \InvalidArgumentException::class, $valid_columns, $empty_columns ],
            [ \InvalidArgumentException::class, $valid_columns, $order_columns ],
            [ \InvalidArgumentException::class, $valid_columns, $alias_columns ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetColumnsFailure
     *
     * @param \Exception    $expected   期待値
     * @param mixed         $prop_value プロパティ columns の値
     * @param IColumns|null $columns    メソッド setColumns の引数 columns に渡す値
     */
    public function testSetColumnsFailure($expected, $prop_value, $columns): void
    {
        $this->expectException($expected);

        /** @var Into $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMNS)->setValue($object, $prop_value);
        $object->setColumns($columns);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
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
     * @param string   $expected    期待値
     * @param ITable   $data_source コンストラクタ data_source に渡す値
     * @param IColumns $columns     コンストラクタ columns に渡す値
     */
    public function testToString($expected, $data_source, $columns): void
    {
        $this->assertSame($expected, (string)new Into($data_source, $columns));
    }
}
