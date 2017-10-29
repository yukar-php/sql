<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Phrases;

use Yukar\Sql\Builder\Common\Objects\Columns;
use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Operators\Alias;
use Yukar\Sql\Builder\Common\Statements\Dml\Select;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Interfaces\Builder\Common\Objects\IDataSource;

/**
 * クラス From の単体テスト
 *
 * @author hiroki sugawara
 */
class FromTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_DATA_SOURCE = 'data_source';

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
    public function testGetPhraseString()
    {
        self::assertSame('FROM %s', $this->getNewInstance()->getPhraseString());
    }

    /**
     * メソッド testGetDataSource のデータプロバイダー
     *
     * @return array
     */
    public function providerGetDataSource()
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
    public function testSetDataSource($expected, $prop_value, $data_source)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_DATA_SOURCE);
        $reflector->setValue($object, $prop_value);
        $object->setDataSource($data_source);

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
        $sub_query_text = new Alias('(SELECT foo FROM table)', 'bar');
        $sub_query_obj = new Alias(new Select(new From(new Table('table')), new Columns([ 'foo' ])), 'bar');

        return [
            [ 'FROM table_name', $table_name ],
            [ 'FROM (SELECT foo FROM table) AS bar', $sub_query_text ],
            [ 'FROM (SELECT foo FROM table) AS bar', $sub_query_obj ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string      $expected    期待値
     * @param IDataSource $data_source コンストラクタの引数 data_source に渡す値
     */
    public function testToString($expected, $data_source)
    {
        self::assertSame($expected, (string)new From($data_source));
    }
}
