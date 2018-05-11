<?php
namespace Yukar\Sql\Tests\Builder\Common\Functions;

use Yukar\Sql\Builder\Common\Functions\BaseFilterableFunction;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * 抽象クラス BaseFilterableFunction の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Functions
 * @author hiroki sugawara
 */
class BaseFilterableFunctionTest extends CustomizedTestCase
{
    private const PROP_NAME_FILTER = 'filter';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return BaseFilterableFunction::class;
    }

    /**
     * メソッド testGetFilter のデータプロバイダー
     *
     * @return array
     */
    public function providerGetFilter(): array
    {
        return [
            [ 'ALL', BaseFilterableFunction::FILTER_ALL ],
            [ 'DISTINCT', BaseFilterableFunction::FILTER_DISTINCT ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetFilter
     *
     * @param string $expected   期待値
     * @param int    $prop_value プロパティ filter の値
     */
    public function testGetFilter($expected, $prop_value): void
    {
        /** @var BaseFilterableFunction $object */
        $object = $this->getNewAbstractInstance();
        $this->getParentProperty($object, self::PROP_NAME_FILTER)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getFilter());
    }

    /**
     * メソッド testSetFilter のデータプロバイダー
     *
     * @return array
     */
    public function providerSetFilter(): array
    {
        return [
            [ 1, null, BaseFilterableFunction::FILTER_ALL ],
            [ 2, 1, BaseFilterableFunction::FILTER_DISTINCT ],
            [ 1, 2, 0 ],
            [ 1, 1, 3 ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetFilter
     *
     * @param string $expected   期待値
     * @param int    $prop_value プロパティ filter の値
     * @param int    $filter     メソッド setFilter の引数 filter に渡す値
     */
    public function testSetFilter($expected, $prop_value, $filter): void
    {
        /** @var BaseFilterableFunction $object */
        $object = $this->getNewAbstractInstance();
        $reflector = $this->getParentProperty($object, self::PROP_NAME_FILTER);
        $reflector->setValue($object, $prop_value);
        $object->setFilter($filter);

        $this->assertSame($expected, $reflector->getValue($object));
    }
}
