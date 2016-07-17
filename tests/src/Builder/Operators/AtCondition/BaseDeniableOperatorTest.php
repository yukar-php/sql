<?php
namespace Yukar\Sql\Tests\Builder\Operators\AtCondition;

use Yukar\Sql\Builder\Operators\AtCondition\BaseDeniableOperator;

/**
 * 抽象クラス BaseDeniableOperator の単体テスト
 *
 * @author hiroki sugawara
 */
class BaseDeniableOperatorTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_IS_NOT = 'is_not';
    const PROP_NAME_BACK_END = 'back_end';

    const METHOD_NAME_IS_BACKEND = 'isBackEnd';
    const METHOD_NAME_SET_IS_BACKEND = 'setIsBackEnd';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return BaseDeniableOperator コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): BaseDeniableOperator
    {
        /** @var BaseDeniableOperator $instance */
        $instance = (new \ReflectionClass($this->getMockForAbstractClass(BaseDeniableOperator::class)))
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
     *
     *
     * @param object $object
     * @param string $method_name
     *
     * @return \ReflectionMethod
     */
    private function getMethod($object, string $method_name): \ReflectionMethod
    {
        $method = (new \ReflectionClass($object))->getParentClass()->getMethod($method_name);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * メソッド testIsNot のデータプロバイダー
     *
     * @return array
     */
    public function providerIsNot()
    {
        return [
            [ true, true ],
            [ false, false ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerIsNot
     *
     * @param bool $expected   期待値
     * @param bool $prop_value プロパティ is_not の値
     */
    public function testIsNot($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_IS_NOT)->setValue($object, $prop_value);

        self::assertSame($expected, $object->isNot());
    }

    /**
     * メソッド testSetIsNot のデータプロバイダー
     *
     * @return array
     */
    public function providerSetIsNot()
    {
        return [
            [ true, false, true ],
            [ false, false, false ],
            [ true, true, true ],
            [ false, true, false ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetIsNot
     *
     * @param bool $expected   期待値
     * @param bool $prop_value プロパティ is_not の値
     * @param bool $is_not     メソッド setIsNot の引数 is_not に渡す値
     */
    public function testSetIsNot($expected, $prop_value, $is_not)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_IS_NOT);
        $reflector->setValue($object, $prop_value);
        $object->setIsNot($is_not);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testIsBackEnd のデータプロバイダー
     *
     * @return array
     */
    public function providerIsBackEnd()
    {
        return [
            [ true, true ],
            [ false, false ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerIsBackEnd
     *
     * @param bool $expected   期待値
     * @param bool $prop_value プロパティ back_end の値
     */
    public function testIsBackEnd($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_BACK_END)->setValue($object, $prop_value);

        self::assertSame($expected, $this->getMethod($object, self::METHOD_NAME_IS_BACKEND)->invoke($object));
    }

    /**
     * メソッド testSetBackEnd のデータプロバイダー
     *
     * @return array
     */
    public function providerSetBackEnd()
    {
        return [
            [ true, false, true ],
            [ false, false, false ],
            [ true, true, true ],
            [ false, true, false ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetBackEnd
     *
     * @param bool $expected   期待値
     * @param bool $prop_value プロパティ back_end の値
     * @param bool $back_end   メソッド setBackEnd の引数 back_end に渡す値
     */
    public function testSetBackEnd($expected, $prop_value, $back_end)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_BACK_END);
        $reflector->setValue($object, $prop_value);
        $method_reflector = $this->getMethod($object, self::METHOD_NAME_SET_IS_BACKEND);
        $method_reflector->invoke($object, $back_end);

        self::assertSame($expected, $reflector->getValue($object));
    }
}
