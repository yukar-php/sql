<?php
namespace Yukar\Sql\Tests\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Builder\Common\Operators\AtCondition\BaseComparableOperator;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * 抽象クラス BaseComparableOperator の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Operators\AtCondition
 * @author hiroki sugawara
 */
class BaseComparableOperatorTest extends CustomizedTestCase
{
    private const PROP_NAME_SIGN = 'sign';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return BaseComparableOperator::class;
    }

    /**
     * メソッド testGetSign のデータプロバイダー
     *
     * @return array
     */
    public function providerGetSign(): array
    {
        return [
            [ '=', BaseComparableOperator::SIGN_EQ ],
            [ '<>', BaseComparableOperator::SIGN_NE ],
            [ '>', BaseComparableOperator::SIGN_GT ],
            [ '>=', BaseComparableOperator::SIGN_AO ],
            [ '<', BaseComparableOperator::SIGN_LT ],
            [ '<=', BaseComparableOperator::SIGN_OU ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetSign
     *
     * @param string $expected   期待値
     * @param int    $prop_value プロパティ sign の値
     */
    public function testGetSign($expected, $prop_value): void
    {
        /** @var BaseComparableOperator $object */
        $object = $this->getNewAbstractInstance();
        $this->getParentProperty($object, self::PROP_NAME_SIGN)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getSign());
    }

    /**
     * メソッド testSetSign のデータプロバイダー
     *
     * @return array
     */
    public function providerSetSign(): array
    {
        return [
            [ 2, 1, BaseComparableOperator::SIGN_NE ],
            [ 3, 2, BaseComparableOperator::SIGN_GT ],
            [ 4, 3, BaseComparableOperator::SIGN_AO ],
            [ 5, 4, BaseComparableOperator::SIGN_LT ],
            [ 6, 5, BaseComparableOperator::SIGN_OU ],
            [ 1, 6, BaseComparableOperator::SIGN_EQ ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetSign
     *
     * @param string $expected   期待値
     * @param int    $prop_value プロパティ sign の値
     * @param int    $sign       メソッド setSign の引数 sign に渡す値
     */
    public function testSetSign($expected, $prop_value, $sign): void
    {
        /** @var BaseComparableOperator $object */
        $object = $this->getNewAbstractInstance();
        $reflector = $this->getParentProperty($object, self::PROP_NAME_SIGN);
        $reflector->setValue($object, $prop_value);
        $object->setSign($sign);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetSignFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetSignFailure(): array
    {
        return [
            [ \InvalidArgumentException::class, BaseComparableOperator::SIGN_EQ, 0 ],
            [ \InvalidArgumentException::class, BaseComparableOperator::SIGN_EQ, 7 ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetSignFailure
     *
     * @param \Exception $expected   期待値
     * @param int        $prop_value プロパティ sign の値
     * @param int        $sign       メソッド setSign の引数 sign に渡す値
     */
    public function testSetSignFailure($expected, $prop_value, $sign): void
    {
        $this->expectException($expected);

        /** @var BaseComparableOperator $object */
        $object = $this->getNewAbstractInstance();
        $this->getParentProperty($object, self::PROP_NAME_SIGN)->setValue($object, $prop_value);
        $object->setSign($sign);
    }
}
