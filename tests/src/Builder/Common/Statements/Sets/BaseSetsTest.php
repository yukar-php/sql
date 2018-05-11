<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Sets;

use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Statements\Dml\Select;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Builder\Common\Statements\Sets\BaseSets;
use Yukar\Sql\Interfaces\Builder\Common\Statements\ISelectQuery;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * 抽象クラス BaseSets の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Statements\Sets
 * @author hiroki sugawara
 */
class BaseSetsTest extends CustomizedTestCase
{
    private const PROP_NAME_IS_ALL = 'is_all';
    private const PROP_NAME_FIRST_QUERY = 'first_query';
    private const PROP_NAME_SECOND_QUERY = 'second_query';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return BaseSets::class;
    }

    /**
     * 正常系テスト
     */
    public function testGetSetsFormat()
    {
        $instance = $this->getNewAbstractInstance();
        $this->assertSame('%s ' . strtoupper(get_class($instance)) . ' %s', $instance->getSetsFormat());
    }

    /**
     * メソッド testGetIsAll のデータプロバイダー
     *
     * @return array
     */
    public function providerGetIsAll(): array
    {
        return [
            [ false, false ],
            [ true, true ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetIsAll
     *
     * @param bool $expected   期待値
     * @param bool $prop_value プロパティ is_all の値
     */
    public function testGetIsAll($expected, $prop_value): void
    {
        /** @var BaseSets $object */
        $object = $this->getNewAbstractInstance();
        $this->getParentProperty($object, self::PROP_NAME_IS_ALL)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getIsAll());
    }

    /**
     * メソッド testSetIsAll のデータプロバイダー
     *
     * @return array
     */
    public function providerSetIsAll(): array
    {
        return [
            [ false, true, false ],
            [ true, false, true ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetIsAll
     *
     * @param bool $expected   期待値
     * @param bool $prop_value プロパティ is_all の値
     * @param bool $is_all     メソッド setIsAll の引数 is_all に渡す値
     */
    public function testSetIsAll($expected, $prop_value, $is_all): void
    {
        /** @var BaseSets $object */
        $object = $this->getNewAbstractInstance();
        $reflector = $this->getParentProperty($object, self::PROP_NAME_IS_ALL);
        $reflector->setValue($object, $prop_value);
        $object->setIsAll($is_all);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testGetFirstQuery のデータプロバイダー
     *
     * @return array
     */
    public function providerGetFirstQuery(): array
    {
        $select = new Select(new From(new Table('table_name')));

        return [
            [ $select, $select ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetFirstQuery
     *
     * @param ISelectQuery $expected   期待値
     * @param ISelectQuery $prop_value プロパティ first_query の値
     */
    public function testGetFirstQuery($expected, $prop_value): void
    {
        /** @var BaseSets $object */
        $object = $this->getNewAbstractInstance();
        $this->getParentProperty($object, self::PROP_NAME_FIRST_QUERY)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getFirstQuery());
    }

    /**
     * メソッド testSetFirstQuery のデータプロバイダー
     *
     * @return array
     */
    public function providerSetFirstQuery(): array
    {
        $select = new Select(new From(new Table('table_name')));

        return [
            [ $select, null, $select ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetFirstQuery
     *
     * @param ISelectQuery $expected   期待値
     * @param mixed        $prop_value プロパティ first_query の値
     * @param ISelectQuery $query      メソッド setFirstQuery の引数 query に渡す値
     */
    public function testSetFirstQuery($expected, $prop_value, $query): void
    {
        /** @var BaseSets $object */
        $object = $this->getNewAbstractInstance();
        $reflector = $this->getParentProperty($object, self::PROP_NAME_FIRST_QUERY);
        $reflector->setValue($object, $prop_value);
        $object->setFirstQuery($query);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testGetSecondQuery のデータプロバイダー
     *
     * @return array
     */
    public function providerGetSecondQuery(): array
    {
        $select = new Select(new From(new Table('table_name')));

        return [
            [ $select, $select ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetSecondQuery
     *
     * @param ISelectQuery $expected   期待値
     * @param ISelectQuery $prop_value プロパティ first_query の値
     */
    public function testGetSecondQuery($expected, $prop_value): void
    {
        /** @var BaseSets $object */
        $object = $this->getNewAbstractInstance();
        $this->getParentProperty($object, self::PROP_NAME_SECOND_QUERY)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getSecondQuery());
    }

    /**
     * メソッド testSetSecondQuery のデータプロバイダー
     *
     * @return array
     */
    public function providerSetSecondQuery(): array
    {
        $select = new Select(new From(new Table('table_name')));

        return [
            [ $select, null, $select ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetSecondQuery
     *
     * @param ISelectQuery $expected   期待値
     * @param mixed        $prop_value プロパティ second_query の値
     * @param ISelectQuery $query      メソッド setSecondQuery の引数 query に渡す値
     */
    public function testSetSecondQuery($expected, $prop_value, $query): void
    {
        /** @var BaseSets $object */
        $object = $this->getNewAbstractInstance();
        $reflector = $this->getParentProperty($object, self::PROP_NAME_SECOND_QUERY);
        $reflector->setValue($object, $prop_value);
        $object->setSecondQuery($query);

        $this->assertSame($expected, $reflector->getValue($object));
    }
}
