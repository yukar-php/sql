<?php
namespace Yukar\Sql\Tests\Builder\Common\Operators;

use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Operators\Alias;
use Yukar\Sql\Builder\Common\Statements\Dml\Select;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス Alias の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Operators
 * @author hiroki sugawara
 */
class AliasTest extends CustomizedTestCase
{
    private const PROP_NAME_ORIGIN_NAME = 'origin_name';
    private const PROP_NAME_ALIAS_NAME = 'alias_name';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return Alias::class;
    }

    /**
     * 正常系テスト
     */
    public function testGetOperatorFormat(): void
    {
        $this->assertSame('%s AS %s', $this->getNewInstance()->getOperatorFormat());
    }

    /**
     * メソッド testGetOriginName のデータプロバイダー
     *
     * @return array
     */
    public function providerGetOriginName(): array
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
    public function testGetOriginName($expected, $prop_value): void
    {
        /** @var Alias $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_ORIGIN_NAME)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getOriginName());
    }

    /**
     * メソッド testSetOriginName のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOriginName(): array
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
    public function testSetOriginName($expected, $prop_value, $origin_name): void
    {
        /** @var Alias $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_ORIGIN_NAME);
        $reflector->setValue($object, $prop_value);
        $object->setOriginName($origin_name);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetOriginNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOriginNameFailure(): array
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
    public function testSetOriginNameFailure($expected, $prop_value, $origin_name): void
    {
        $this->expectException($expected);

        /** @var Alias $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_ORIGIN_NAME)->setValue($object, $prop_value);
        $object->setOriginName($origin_name);
    }

    /**
     * メソッド testGetAliasName のデータプロバイダー
     *
     * @return array
     */
    public function providerGetAliasName(): array
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
    public function testGetAliasName($expected, $prop_value): void
    {
        /** @var Alias $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_ALIAS_NAME)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getAliasName());
    }

    /**
     * メソッド testSetAliasName のデータプロバイダー
     *
     * @return array
     */
    public function providerSetAliasName(): array
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
    public function testSetAliasName($expected, $prop_value, $alias_name): void
    {
        /** @var Alias $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_ALIAS_NAME);
        $reflector->setValue($object, $prop_value);
        $object->setAliasName($alias_name);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetAliasNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetAliasNameFailure(): array
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
    public function testSetAliasNameFailure($expected, $prop_value, $alias_name): void
    {
        $this->expectException($expected);

        /** @var Alias $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_ALIAS_NAME)->setValue($object, $prop_value);
        $object->setAliasName($alias_name);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
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
    public function testToString($expected, $origin_name, $alias_name): void
    {
        $this->assertSame($expected, (string)new Alias($origin_name, $alias_name));
    }
}
