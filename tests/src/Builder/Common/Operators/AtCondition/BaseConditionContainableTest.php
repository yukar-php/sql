<?php
namespace Yukar\Sql\Tests\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Builder\Common\Operators\AtCondition\BaseConditionContainable;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * 抽象クラス BaseConditionContainable の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Operators\AtCondition
 * @author hiroki sugawara
 */
class BaseConditionContainableTest extends CustomizedTestCase
{
    private const PROP_NAME_NAME = 'name';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return BaseConditionContainable::class;
    }

    /**
     * 正常系テスト
     */
    public function testGetOperatorFormat(): void
    {
        $this->assertSame('%s %s %s', $this->getNewAbstractInstance()->getOperatorFormat());
    }

    /**
     * メソッド testGetName のデータプロバイダー
     *
     * @return array
     */
    public function providerGetName(): array
    {
        return [
            [ 'column', 'column' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetName
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ name の値
     */
    public function testGetName($expected, $prop_value): void
    {
        /** @var BaseConditionContainable $object */
        $object = $this->getNewAbstractInstance();
        $this->getParentProperty($object, self::PROP_NAME_NAME)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getName());
    }

    /**
     * メソッド testSetName のデータプロバイダー
     *
     * @return array
     */
    public function providerSetName(): array
    {
        return [
            [ 'column', null, 'column' ],
            [ 'abc', 'column', 'abc' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetName
     *
     * @param string $expected   期待値
     * @param mixed  $prop_value プロパティ name の値
     * @param string $name       メソッド setName の引数 name に渡す値
     */
    public function testSetName($expected, $prop_value, $name): void
    {
        /** @var BaseConditionContainable $object */
        $object = $this->getNewAbstractInstance();
        $reflector = $this->getParentProperty($object, self::PROP_NAME_NAME);
        $reflector->setValue($object, $prop_value);
        $object->setName($name);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetNameFailure(): array
    {
        return [
            [ \InvalidArgumentException::class, null, '' ],
            [ \InvalidArgumentException::class, 'column', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetNameFailure
     *
     * @param \Exception $expected   期待値
     * @param mixed      $prop_value プロパティ name の値
     * @param mixed      $name       メソッド setName の引数 name に渡す値
     */
    public function testSetNameFailure($expected, $prop_value, $name): void
    {
        $this->expectException($expected);

        /** @var BaseConditionContainable $object */
        $object = $this->getNewAbstractInstance();
        $this->getParentProperty($object, self::PROP_NAME_NAME)->setValue($object, $prop_value);
        $object->setName($name);
    }
}
