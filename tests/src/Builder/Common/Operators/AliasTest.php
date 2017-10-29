<?php
namespace Yukar\Sql\Tests\Builder\Operators;

use Yukar\Sql\Builder\Objects\Table;
use Yukar\Sql\Builder\Operators\Alias;
use Yukar\Sql\Builder\Statements\Dml\Select;
use Yukar\Sql\Builder\Statements\Phrases\From;

/**
 * クラス Alias の単体テスト
 *
 * @author hiroki sugawara
 */
class AliasTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_ORIGIN_NAME = 'origin_name';
    const PROP_NAME_ALIAS_NAME = 'alias_name';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Alias コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Alias
    {
        /** @var Alias $instance */
        $instance = (new \ReflectionClass(Alias::class))->newInstanceWithoutConstructor();

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
    public function testGetOperatorFormat()
    {
        self::assertSame('%s AS %s', $this->getNewInstance()->getOperatorFormat());
    }

    /**
     * メソッド testGetOriginName のデータプロバイダー
     *
     * @return array
     */
    public function providerGetOriginName()
    {
        $table_name = new Table('table_name');
        $select_query = new Select(new From($table_name));

        return [
            [ 'table_name', $table_name ],
            [ '(SELECT * FROM table_name)', '(SELECT * FROM table_name)' ],
            [ '(SELECT * FROM table_name)', $select_query ],
            [ 'origin_name', 'origin_name' ],
            [ '(SELECT * FROM table_name)', '(SELECT * FROM table_name)' ],
            [ '(SELECT * FROM table_name)', $select_query ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetOriginName
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ origin_name の値
     */
    public function testGetOriginName($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_ORIGIN_NAME)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getOriginName());
    }

    /**
     * メソッド testSetOriginName のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOriginName()
    {
        $table_name = new Table('table_name');
        $select_query = new Select(new From($table_name));

        return [
            [ $table_name, null, $table_name ],
            [ '(SELECT * FROM table_name)', null, '(SELECT * FROM table_name)' ],
            [ $select_query, null, $select_query ],
            [ 'origin_name', $table_name, 'origin_name' ],
            [ '(SELECT * FROM table_name)', $table_name, '(SELECT * FROM table_name)' ],
            [ $select_query, 'table_name', $select_query ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetOriginName
     *
     * @param string $expected    期待値
     * @param mixed  $prop_value  プロパティ origin_name の値
     * @param string $origin_name メソッド setOriginName の引数 origin_name に渡す値
     */
    public function testSetOriginName($expected, $prop_value, $origin_name)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_ORIGIN_NAME);
        $reflector->setValue($object, $prop_value);
        $object->setOriginName($origin_name);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetOriginNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOriginNameFailure()
    {
        return [
            [ \InvalidArgumentException::class, null, '' ],
            [ \InvalidArgumentException::class, 'origin', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetOriginNameFailure
     *
     * @param |Exception $expected    期待値
     * @param mixed      $prop_value  プロパティ origin_name の値
     * @param string     $origin_name メソッド setOriginName の引数 origin_name に渡す値
     */
    public function testSetOriginNameFailure($expected, $prop_value, $origin_name)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_ORIGIN_NAME)->setValue($object, $prop_value);
        $object->setOriginName($origin_name);
    }

    /**
     * メソッド testGetAliasName のデータプロバイダー
     *
     * @return array
     */
    public function providerGetAliasName()
    {
        return [
            [ 'alias_name', 'alias_name' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetAliasName
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ alias_name の値
     */
    public function testGetAliasName($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_ALIAS_NAME)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getAliasName());
    }

    /**
     * メソッド testSetAliasName のデータプロバイダー
     *
     * @return array
     */
    public function providerSetAliasName()
    {
        return [
            [ 'alias_name', null, 'alias_name' ],
            [ 'second_alias', 'first_alias', 'second_alias' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetAliasName
     *
     * @param string $expected   期待値
     * @param mixed  $prop_value プロパティ alias_name の値
     * @param string $alias_name メソッド setAliasName の引数 alias_name に渡す値
     */
    public function testSetAliasName($expected, $prop_value, $alias_name)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_ALIAS_NAME);
        $reflector->setValue($object, $prop_value);
        $object->setAliasName($alias_name);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetAliasNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetAliasNameFailure()
    {
        return [
            [ \InvalidArgumentException::class, null, '' ],
            [ \InvalidArgumentException::class, 'alias_name', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetAliasNameFailure
     *
     * @param \Exception $expected   期待値
     * @param mixed      $prop_value プロパティ alias_name の値
     * @param string     $alias_name メソッド setAliasName の引数 alias_name に渡す値
     */
    public function testSetAliasNameFailure($expected, $prop_value, $alias_name)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_ALIAS_NAME)->setValue($object, $prop_value);
        $object->setAliasName($alias_name);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        $table_name = new Table('table_name');
        $select_query = new Select(new From($table_name));

        return [
            [ 'origin_a AS alias_a', 'origin_a', 'alias_a' ],
            [ 'table_name AS alias_table', $table_name, 'alias_table' ],
            [ '(SELECT * FROM table_name) AS alias_table', $select_query, 'alias_table' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected    期待値
     * @param string $origin_name コンストラクタの引数 origin_name に渡す値
     * @param string $alias_name  コンストラクタの引数 alias_name に渡す値
     */
    public function testToString($expected, $origin_name, $alias_name)
    {
        self::assertSame($expected, (string)new Alias($origin_name, $alias_name));
    }
}
