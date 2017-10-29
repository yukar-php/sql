<?php
namespace Yukar\Sql\Tests\Builder\Common\Objects;

use Yukar\Sql\Builder\Common\Objects\Columns;
use Yukar\Sql\Builder\Common\Objects\DelimitedIdentifier;
use Yukar\Sql\Builder\Common\Operators\Alias;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Between;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Expression;
use Yukar\Sql\Builder\Common\Operators\Order;

/**
 * クラス Columns の単体テスト
 *
 * @author hiroki sugawara
 */
class ColumnsTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_COLUMN_LIST = 'column_list';

    /**
     * 単体テスト対象となるクラスのテストが全て終わった時に最後に実行します。
     */
    public static function tearDownAfterClass()
    {
        $object = new \ReflectionClass(DelimitedIdentifier::class);
        $property = $object->getProperty('quote_type');
        $property->setAccessible(true);
        $property->setValue($object, null);
    }

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Columns コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Columns
    {
        /** @var Columns $instance */
        $instance = (new \ReflectionClass(Columns::class))->newInstanceWithoutConstructor();

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
     * メソッド testGetColumns のデータプロバイダー
     *
     * @return array
     */
    public function providerGetColumns()
    {
        $order_obj = new Order('x', Order::DESCENDING);

        return [
            [ [ 'a', 'b', 'c' ], [ 'a', 'b', 'c' ] ],
            [ [ $order_obj, 'y' ], [ $order_obj, 'y' ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetColumns
     *
     * @param array $expected  期待値
     * @param array $base_list プロパティ column_list の値
     */
    public function testGetColumns($expected, $base_list)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN_LIST)->setValue($object, $base_list);

        self::assertSame($expected, $object->getColumns());
    }

    /**
     * メソッド testSetColumns のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumns()
    {
        return [
            [ [ 'a', 'b', 'c' ], [], [ 'a', 'b', 'c' ] ],
            [ [ '1', '2', '3' ], [], [ '1', '2', '3' ] ],
            [ [ 'x', 'y' ], [ 'a', 'b', 'c' ], [ 'x', 'y' ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetColumns
     *
     * @param array $expected   期待値
     * @param array $prop_value プロパティ column_list の値
     * @param array $set_list   メソッド setColumns の引数 columns に渡す値
     */
    public function testSetColumns($expected, $prop_value, $set_list)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_COLUMN_LIST);
        $reflector->setValue($object, $prop_value);
        $object->setColumns($set_list);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetColumnsFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumnsFailure()
    {
        return [
            [ \InvalidArgumentException::class, [], [] ],
            [ \InvalidArgumentException::class, [ 'a', 'b' ], [] ],
            [ \DomainException::class, [], [ 0 ] ],
            [ \DomainException::class, [ '1', '2' ], [ 0, 1 ] ],
            [ \DomainException::class, [ '1', '2' ], [ new Expression('a', 1), new Between('b', 1, 2) ] ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetColumnsFailure
     *
     * @param \Exception $expected   期待値
     * @param array      $prop_value プロパティ column_list の値
     * @param array      $set_list   メソッド setColumns の引数 columns に渡す値
     */
    public function testSetColumnsFailure($expected, $prop_value, $set_list)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN_LIST)->setValue($object, $prop_value);
        $object->setColumns($set_list);
    }

    /**
     * testHasOnlyStringItems のデータプロバイダー
     *
     * @return array
     */
    public function providerHasOnlyStringItems()
    {
        return [
            [ false, [] ],
            [ false, [ '' ] ],
            [ true, [ 'a', 'b', 'c' ] ],
            [ false, [ new Order('col_name', Order::DESCENDING) ] ],
            [ false, [ new Alias('orig_table', 'as_table') ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerHasOnlyStringItems
     *
     * @param bool  $expected   期待値
     * @param array $prop_value プロパティ column_list の値
     */
    public function testHasOnlyStringItems($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN_LIST)->setValue($object, $prop_value);

        self::assertSame($expected, $object->hasOnlyStringItems());
    }

    /**
     * testHasOnlyOrderByItems のデータプロバイダー
     *
     * @return array
     */
    public function providerHasOnlyOrderByItems()
    {
        return [
            [ false, [] ],
            [ false, [ '' ] ],
            [ true, [ 'a', 'b', 'c' ] ],
            [ true, [ new Order('col_name', Order::DESCENDING) ] ],
            [ false, [ new Alias('orig_table', 'as_table') ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerHasOnlyOrderByItems
     *
     * @param bool  $expected   期待値
     * @param array $prop_value プロパティ column_list の値
     */
    public function testHasOnlyOrderByItems($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN_LIST)->setValue($object, $prop_value);

        self::assertSame($expected, $object->hasOnlyOrderByItems());
    }

    /**
     * testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        $alias_a = new Alias('a', 'alias_a');
        $alias_b = new Alias('b', 'alias_b');
        $order_asc = new Order('o');
        $order_dsc = new Order('p', Order::DESCENDING);

        return [
            [ "*", [], null ],
            [ 'a, b, c', [ 'a', 'b', 'c' ], null ],
            [ 't.a, t.b, t.c', [ 't.a', 't.b', 't.c' ], null ],
            [ '"a", "b", "c"', [ 'a', 'b', 'c' ], DelimitedIdentifier::ANSI_QUOTES_TYPE ],
            [ '"t"."a", "t"."b", "t"."c"', [ 't.a', 't.b', 't.c' ], DelimitedIdentifier::ANSI_QUOTES_TYPE ],
            [ '`a`, `b`, `c`', [ 'a', 'b', 'c' ], DelimitedIdentifier::MYSQL_QUOTES_TYPE ],
            [ '`t`.`a`, `t`.`b`, `t`.`c`', [ 't.a', 't.b', 't.c' ], DelimitedIdentifier::MYSQL_QUOTES_TYPE ],
            [ '[a], [b], [c]', [ 'a', 'b', 'c' ], DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE ],
            [ '[t].[a], [t].[b], [t].[c]', [ 't.a', 't.b', 't.c' ], DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE ],
            [ 'a AS alias_a, b AS alias_b', [ $alias_a, $alias_b ], null ],
            [ 'a AS alias_a, b AS alias_b', [ $alias_a, $alias_b ], DelimitedIdentifier::ANSI_QUOTES_TYPE ],
            [ 'o ASC, p DESC', [ $order_asc, $order_dsc ], null ],
            [ 'o ASC, p DESC', [ $order_asc, $order_dsc ], DelimitedIdentifier::ANSI_QUOTES_TYPE ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected   期待値
     * @param array  $base_list  コンストラクタの引数 columns に渡す値
     * @param int    $quote_type メソッド init の引数 quote_type に渡す値
     */
    public function testToString($expected, $base_list, $quote_type)
    {
        DelimitedIdentifier::init($quote_type ?? DelimitedIdentifier::NONE_QUOTES_TYPE);

        self::assertSame($expected, (string)new Columns($base_list, DelimitedIdentifier::get()));
    }
}
