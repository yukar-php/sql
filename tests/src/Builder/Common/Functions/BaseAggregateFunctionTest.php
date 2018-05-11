<?php
namespace Yukar\Sql\Tests\Builder\Common\Functions;

use Yukar\Sql\Builder\Common\Functions\BaseAggregateFunction;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * 抽象クラス BaseAggregateFunction の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Functions
 * @author hiroki sugawara
 */
class BaseAggregateFunctionTest extends CustomizedTestCase
{
    private const PROP_NAME_COLUMN = 'column';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return BaseAggregateFunction::class;
    }

    /**
     * 正常系テスト
     */
    public function testGetFunctionName(): void
    {
        /** @var BaseAggregateFunction $instance */
        $instance = $this->getNewAbstractInstance();
        $this->assertSame(strtoupper(get_class($instance)), $instance->getFunctionName());
    }

    /**
     * 正常系テスト
     */
    public function testGetFunctionFormat(): void
    {
        $this->assertSame('%s(%s)', $this->getNewAbstractInstance()->getFunctionFormat());
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
        /** @var BaseAggregateFunction $instance */
        $object = $this->getNewAbstractInstance();
        $this->getParentProperty($object, self::PROP_NAME_COLUMN)->setValue($object, $prop_value);

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
        /** @var BaseAggregateFunction $instance */
        $object = $this->getNewAbstractInstance();
        $reflector = $this->getParentProperty($object, self::PROP_NAME_COLUMN);
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

        /** @var BaseAggregateFunction $instance */
        $object = $this->getNewAbstractInstance();
        $this->getParentProperty($object, self::PROP_NAME_COLUMN)->setValue($object, $prop_value);
        $object->setColumn($column);
    }
}
