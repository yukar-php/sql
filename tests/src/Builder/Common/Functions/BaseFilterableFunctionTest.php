<?php
namespace Yukar\Sql\Tests\Builder\Functions;

use Yukar\Sql\Builder\Functions\BaseFilterableFunction;

/**
 * 抽象クラス BaseFilterableFunction の単体テスト
 *
 * @author hiroki sugawara
 */
class BaseFilterableFunctionTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_FILTER = 'filter';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return BaseFilterableFunction コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): BaseFilterableFunction
    {
        /** @var BaseFilterableFunction $instance */
        $instance = (new \ReflectionClass($this->getMockForAbstractClass(BaseFilterableFunction::class)))
            ->newInstanceWithoutConstructor();

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
        $property = (new \ReflectionClass($object))->getParentClass()->getProperty($property_name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * メソッド testGetFilter のデータプロバイダー
     *
     * @return array
     */
    public function providerGetFilter()
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
    public function testGetFilter($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_FILTER)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getFilter());
    }

    /**
     * メソッド testSetFilter のデータプロバイダー
     *
     * @return array
     */
    public function providerSetFilter()
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
    public function testSetFilter($expected, $prop_value, $filter)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_FILTER);
        $reflector->setValue($object, $prop_value);
        $object->setFilter($filter);

        self::assertSame($expected, $reflector->getValue($object));
    }
}
