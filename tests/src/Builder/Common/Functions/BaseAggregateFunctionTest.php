<?php
namespace Yukar\Sql\Tests\Builder\Common\Functions;

use Yukar\Sql\Builder\Common\Functions\BaseAggregateFunction;

/**
 * 抽象クラス BaseAggregateFunction の単体テスト
 *
 * @author hiroki sugawara
 */
class BaseAggregateFunctionTest extends \PHPUnit_Framework_TestCase
{
    private const PROP_NAME_COLUMN = 'column';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return BaseAggregateFunction コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): BaseAggregateFunction
    {
        /** @var BaseAggregateFunction $instance */
        $instance = (new \ReflectionClass($this->getMockForAbstractClass(BaseAggregateFunction::class)))
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
     * 正常系テスト
     */
    public function testGetFunctionName(): void
    {
        $instance = $this->getNewInstance();
        $this->assertSame(strtoupper(get_class($instance)), $instance->getFunctionName());
    }

    /**
     * 正常系テスト
     */
    public function testGetFunctionFormat(): void
    {
        $this->assertSame('%s(%s)', $this->getNewInstance()->getFunctionFormat());
    }

    /**
     * メソッド testGetColumn のデータプロバイダー
     *
     * @return array
     */
    public function providerGetColumn(): array
    {
        return [
            [ 'column', 'column' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetColumn
     *
     * @param string $expected   期待値
     * @param int    $prop_value プロパティ column の値
     */
    public function testGetColumn($expected, $prop_value): void
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getColumn());
    }

    /**
     * メソッド testSetColumn のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumn(): array
    {
        return [
            [ 'column', '', 'column' ],
            [ 'value', 'column', 'value' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetColumn
     *
     * @param string $expected   期待値
     * @param int    $prop_value プロパティ column の値
     * @param int    $column     メソッド setColumn の引数 column に渡す値
     */
    public function testSetColumn($expected, $prop_value, $column): void
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_COLUMN);
        $reflector->setValue($object, $prop_value);
        $object->setColumn($column);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetColumnFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetColumnFailure(): array
    {
        return [
            [ \InvalidArgumentException::class, null, '' ],
            [ \InvalidArgumentException::class, 'column', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetColumnFailure
     *
     * @param \Exception $expected   期待値
     * @param mixed      $prop_value プロパティ column の値
     * @param mixed      $column     メソッド setColumn の引数 column に渡す値
     */
    public function testSetColumnFailure($expected, $prop_value, $column): void
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_COLUMN)->setValue($object, $prop_value);
        $object->setColumn($column);
    }
}
