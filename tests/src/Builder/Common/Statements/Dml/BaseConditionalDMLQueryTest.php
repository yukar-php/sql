<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Dml;

use Yukar\Sql\Builder\Common\Objects\Conditions;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Expression;
use Yukar\Sql\Builder\Common\Statements\Dml\BaseConditionalDMLQuery;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ICondition;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * 抽象クラス BaseConditionalDMLQuery の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Statements\Dml
 * @author hiroki sugawara
 */
class BaseConditionalDMLQueryTest extends CustomizedTestCase
{
    private const PROP_NAME_WHERE = 'where';
    private const METHOD_NAME_SET_WHERE_CONDITION = 'setWhereCondition';
    private const METHOD_NAME_GET_WHERE_STRING = 'getWhereString';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return BaseConditionalDMLQuery::class;
    }

    /**
     * メソッド testGetWhere のデータプロバイダー
     *
     * @return array
     */
    public function providerGetWhere(): array
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
    public function testGetWhere($expected, $prop_value): void
    {
        /** @var BaseConditionalDMLQuery $object */
        $object = $this->getNewAbstractInstance();
        $this->getParentProperty($object, self::PROP_NAME_WHERE)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getWhere());
    }

    /**
     * メソッド testSetWhereCondition のデータプロバイダー
     *
     * @return array
     */
    public function providerSetWhereCondition(): array
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
    public function testSetWhereCondition($expected, $prop_value, $where): void
    {
        /** @var BaseConditionalDMLQuery $object */
        $object = $this->getNewAbstractInstance();
        $reflector = $this->getParentProperty($object, self::PROP_NAME_WHERE);
        $reflector->setValue($object, $prop_value);
        $method = $this->getParentMethod($object, self::METHOD_NAME_SET_WHERE_CONDITION);
        $method->invoke($object, $where);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetWhereConditionFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetWhereConditionFailure(): array
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
    public function testSetWhereConditionFailure($expected, $prop_value, $where): void
    {
        $this->expectException($expected);

        /** @var BaseConditionalDMLQuery $object */
        $object = $this->getNewAbstractInstance();
        $this->getParentProperty($object, self::PROP_NAME_WHERE)->setValue($object, $prop_value);
        $method = $this->getParentMethod($object, self::METHOD_NAME_SET_WHERE_CONDITION);
        $method->invoke($object, $where);
    }

    /**
     * メソッド testGetWhereString のデータプロバイダー
     *
     * @return array
     */
    public function providerGetWhereString(): array
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
    public function testGetWhereString($expected, $prop_value): void
    {
        /** @var BaseConditionalDMLQuery $object */
        $object = $this->getNewAbstractInstance();
        $this->getParentProperty($object, self::PROP_NAME_WHERE)->setValue($object, $prop_value);

        $this->assertSame(
            $expected,
            $this->getParentMethod($object, self::METHOD_NAME_GET_WHERE_STRING)->invoke($object)
        );
    }
}
