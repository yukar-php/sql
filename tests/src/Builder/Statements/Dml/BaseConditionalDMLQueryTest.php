<?php
namespace Yukar\Sql\Tests\Builder\Statements\Dml;

use Yukar\Sql\Builder\Objects\Conditions;
use Yukar\Sql\Builder\Operators\AtCondition\Expression;
use Yukar\Sql\Builder\Statements\Dml\BaseConditionalDMLQuery;
use Yukar\Sql\Interfaces\Builder\Objects\ICondition;

/**
 * 抽象クラス BaseConditionalDMLQuery の単体テスト
 *
 * @author hiroki sugawara
 */
class BaseConditionalDMLQueryTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_WHERE = 'where';
    const METHOD_NAME_SET_WHERE_CONDITION = 'setWhereCondition';
    const METHOD_NAME_GET_WHERE_STRING = 'getWhereString';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return BaseConditionalDMLQuery コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): BaseConditionalDMLQuery
    {
        /** @var BaseConditionalDMLQuery $instance */
        $instance = (new \ReflectionClass($this->getMockForAbstractClass(BaseConditionalDMLQuery::class)))
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
     * 単体テスト対象となるクラスの指定した名前のメソッドのリフレクションインスタンスを取得します。
     *
     * @param object $object      単体テスト対象となるクラスのインスタンス
     * @param string $method_name リフレクションを取得するメソッドの名前
     *
     * @return \ReflectionMethod 指定した名前のメソッドのリフレクションを持つインスタンス
     */
    private function getMethod($object, string $method_name): \ReflectionMethod
    {
        $method = (new \ReflectionClass($object))->getParentClass()->getMethod($method_name);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * メソッド testGetWhere のデータプロバイダー
     *
     * @return array
     */
    public function providerGetWhere()
    {
        $one_condition = (new Conditions())->addCondition(new Expression('a', 1));
        $two_condition = (new Conditions())->addCondition(new Expression('a', 1))->addCondition(new Expression('b', 1));
        $set_condition = (new Conditions())->setConditions(new Expression('x', 10), new Expression('y', 10));

        return [
            [ $one_condition, $one_condition ],
            [ $two_condition, $two_condition ],
            [ $set_condition, $set_condition ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetWhere
     *
     * @param ICondition $expected   期待値
     * @param ICondition $prop_value プロパティ where の値
     */
    public function testGetWhere($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_WHERE)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getWhere());
    }

    /**
     * メソッド testSetWhereCondition のデータプロバイダー
     *
     * @return array
     */
    public function providerSetWhereCondition()
    {
        $one_condition = (new Conditions())->addCondition(new Expression('a', 1));
        $two_condition = (new Conditions())->addCondition(new Expression('a', 1))->addCondition(new Expression('b', 1));
        $set_condition = (new Conditions())->setConditions(new Expression('x', 10), new Expression('y', 10));

        return [
            [ $one_condition, null, $one_condition ],
            [ $two_condition, null, $two_condition ],
            [ $set_condition, null, $set_condition ],
            [ $one_condition, $set_condition, $one_condition ],
            [ $two_condition, $one_condition, $two_condition ],
            [ $set_condition, $two_condition, $set_condition ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetWhereCondition
     *
     * @param ICondition $expected   期待値
     * @param ICondition $prop_value プロパティ where の値
     * @param ICondition $where      メソッド setWhere の引数 condition に渡す値
     */
    public function testSetWhereCondition($expected, $prop_value, $where)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_WHERE);
        $reflector->setValue($object, $prop_value);
        $method = $this->getMethod($object, self::METHOD_NAME_SET_WHERE_CONDITION);
        $method->invoke($object, $where);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetWhereConditionFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetWhereConditionFailure()
    {
        return [
            [ \InvalidArgumentException::class, null, new Conditions() ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetWhereConditionFailure
     *
     * @param ICondition $expected   期待値
     * @param ICondition $prop_value プロパティ where の値
     * @param ICondition $where      メソッド setWhere の引数 condition に渡す値
     */
    public function testSetWhereConditionFailure($expected, $prop_value, $where)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_WHERE)->setValue($object, $prop_value);
        $method = $this->getMethod($object, self::METHOD_NAME_SET_WHERE_CONDITION);
        $method->invoke($object, $where);
    }

    /**
     * メソッド testGetWhereString のデータプロバイダー
     *
     * @return array
     */
    public function providerGetWhereString()
    {
        $one_condition = (new Conditions())->addCondition(new Expression('a', 1));
        $two_condition = (new Conditions())->addCondition(new Expression('a', 1))->addCondition(new Expression('b', 1));
        $set_condition = (new Conditions())->setConditions(new Expression('x', 10), new Expression('y', 10));

        return [
            [ 'WHERE a = 1', $one_condition ],
            [ 'WHERE a = 1 AND b = 1', $two_condition ],
            [ 'WHERE x = 10 AND y = 10', $set_condition ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetWhereString
     *
     * @param string     $expected   期待値
     * @param ICondition $prop_value プロパティ where の値
     */
    public function testGetWhereString($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_WHERE)->setValue($object, $prop_value);

        self::assertSame($expected, $this->getMethod($object, self::METHOD_NAME_GET_WHERE_STRING)->invoke($object));
    }
}
