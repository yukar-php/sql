<?php
namespace Yukar\Sql\Tests\Builder\Operators\AtCondition;

use Yukar\Sql\Builder\Operators\AtCondition\BaseComparableOperator;

/**
 * 抽象クラス BaseComparableOperator の単体テスト
 *
 * @author hiroki sugawara
 */
class BaseComparableOperatorTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_SIGN = 'sign';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return BaseComparableOperator コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): BaseComparableOperator
    {
        /** @var BaseComparableOperator $instance */
        $instance = (new \ReflectionClass($this->getMockForAbstractClass(BaseComparableOperator::class)))
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
     * メソッド testGetSign のデータプロバイダー
     *
     * @return array
     */
    public function providerGetSign()
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
    public function testGetSign($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_SIGN)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getSign());
    }

    /**
     * メソッド testSetSign のデータプロバイダー
     *
     * @return array
     */
    public function providerSetSign()
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
    public function testSetSign($expected, $prop_value, $sign)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_SIGN);
        $reflector->setValue($object, $prop_value);
        $object->setSign($sign);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetSignFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetSignFailure()
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
    public function testSetSignFailure($expected, $prop_value, $sign)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_SIGN)->setValue($object, $prop_value);
        $object->setSign($sign);
    }
}
