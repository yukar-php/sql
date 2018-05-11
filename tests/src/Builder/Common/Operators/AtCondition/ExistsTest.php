<?php
namespace Yukar\Sql\Tests\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Builder\Common\Objects\Columns;
use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Exists;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Not;
use Yukar\Sql\Builder\Common\Statements\Dml\Select;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス Exists の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Operators\AtCondition
 * @author hiroki sugawara
 */
class ExistsTest extends CustomizedTestCase
{
    private const PROP_NAME_NEEDLE = 'needle';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return Exists::class;
    }

    /**
     * メソッド testSetNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetNameFailure(): array
    {
        return [
            [ \BadMethodCallException::class, 'name' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetNameFailure
     *
     * @param |Exception $expected 期待値
     * @param mixed      $name     メソッド setName の引数 name に渡す値
     */
    public function testSetNameFailure($expected, $name): void
    {
        $this->expectException($expected);
        $this->getNewInstance()->setName($name);
    }

    /**
     * メソッド testGetNeedle のデータプロバイダー
     *
     * @return array
     */
    public function providerGetNeedle(): array
    {
        $table_name = new Table('table_name');
        $select_query_a = new Select(new From($table_name));
        $select_query_b = new Select(new From($table_name), new Columns([ 'col' ]));

        return [
            [ 'SELECT * FROM table_name', 'SELECT * FROM table_name' ],
            [ 'SELECT * FROM table_name', $select_query_a ],
            [ 'SELECT col FROM table_name', $select_query_b ],
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
    public function testGetNeedle($expected, $prop_value): void
    {
        /** @var Exists $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_NEEDLE)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getNeedle());
    }

    /**
     * メソッド testSetNeedle のデータプロバイダー
     *
     * @return array
     */
    public function providerSetNeedle(): array
    {
        $table_name = new Table('table_name');
        $select_query_a = new Select(new From($table_name));
        $select_query_b = new Select(new From($table_name), new Columns([ 'col' ]));

        return [
            [ 'SELECT * FROM table_name', null, 'SELECT * FROM table_name' ],
            [ $select_query_a, null, $select_query_a ],
            [ 'SELECT col FROM table_name', $select_query_a, 'SELECT col FROM table_name' ],
            [ $select_query_b, 'SELECT * FROM table_name', $select_query_b ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetNeedle
     *
     * @param string $expected   期待値
     * @param mixed  $prop_value プロパティ needle の値
     * @param mixed  $needle     メソッド setNeedle の引数 needle に渡す値
     */
    public function testSetNeedle($expected, $prop_value, $needle): void
    {
        /** @var Exists $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_NEEDLE);
        $reflector->setValue($object, $prop_value);
        $object->setNeedle($needle);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetNeedleFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetNeedleFailure(): array
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
     * @param |Exception $expected   期待値
     * @param mixed      $prop_value プロパティ needle の値
     * @param mixed      $needle     メソッド setNeedle の引数 needle に渡す値
     */
    public function testSetNeedleFailure($expected, $prop_value, $needle): void
    {
        $this->expectException($expected);

        /** @var Exists $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_NEEDLE)->setValue($object, $prop_value);
        $object->setNeedle($needle);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        $select = new Select(new From(new Table('table')), new Columns([ 'col' ]));

        return [
            [ 'EXISTS (SELECT col FROM table)', 'SELECT col FROM table', false ],
            [ 'EXISTS (SELECT col FROM table)', $select, false ],
            [ 'NOT EXISTS (SELECT col FROM table)', 'SELECT col FROM table', true ],
            [ 'NOT EXISTS (SELECT col FROM table)', $select, true ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected 期待値
     * @param mixed  $needle   コンストラクタの引数 needle に渡す値
     * @param bool   $is_not   コンストラクタの引数 is_not に渡す値
     */
    public function testToString($expected, $needle, $is_not): void
    {
        $exists = new Exists($needle);
        ($is_not === true) && $exists = new Not($exists);

        $this->assertSame($expected, (string)$exists);
    }
}
