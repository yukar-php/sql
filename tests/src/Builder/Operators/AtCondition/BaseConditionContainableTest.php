<?php
namespace Yukar\Sql\Tests\Builder\Operators\AtCondition;

use Yukar\Sql\Builder\Operators\AtCondition\BaseConditionContainable;

/**
 * 抽象クラス BaseConditionContainable の単体テスト
 *
 * @author hiroki sugawara
 */
class BaseConditionContainableTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_NAME = 'name';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return BaseConditionContainable コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): BaseConditionContainable
    {
        /** @var BaseConditionContainable $instance */
        $instance = (new \ReflectionClass($this->getMockForAbstractClass(BaseConditionContainable::class)))
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
    public function testGetOperatorFormat()
    {
        self::assertSame('%s %s %s', $this->getNewInstance()->getOperatorFormat());
    }

    /**
     * メソッド testGetName のデータプロバイダー
     *
     * @return array
     */
    public function providerGetName()
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
    public function testGetName($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_NAME)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getName());
    }

    /**
     * メソッド testSetName のデータプロバイダー
     *
     * @return array
     */
    public function providerSetName()
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
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ name の値
     * @param string $name      メソッド setName の引数 name に渡す値
     */
    public function testSetName($expected, $prop_value, $name)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_NAME);
        $reflector->setValue($object, $prop_value);
        $object->setName($name);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetNameFailure()
    {
        return [
            [ '\InvalidArgumentException', null, '' ],
            [ '\InvalidArgumentException', 'column', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetNameFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ name の値
     * @param mixed $name          メソッド setName の引数 name に渡す値
     */
    public function testSetNameFailure($expected, $prop_value, $name)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_NAME)->setValue($object, $prop_value);
        $object->setName($name);
    }
}
