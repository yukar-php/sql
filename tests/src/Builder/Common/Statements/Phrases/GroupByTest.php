<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Phrases;

use Yukar\Sql\Builder\Common\Objects\Columns;
use Yukar\Sql\Builder\Common\Objects\Conditions;
use Yukar\Sql\Builder\Common\Operators\Alias;
use Yukar\Sql\Builder\Common\Operators\Order;
use Yukar\Sql\Builder\Common\Statements\Phrases\GroupBy;
use Yukar\Sql\Interfaces\Builder\Common\Objects\IColumns;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ICondition;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス GroupBy の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Statements\Phrases
 * @author hiroki sugawara
 */
class GroupByTest extends CustomizedTestCase
{
    private const PROP_NAME_GROUP_BY_LIST = 'group_by_list';
    private const PROP_NAME_HAVING_COND = 'having_cond';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return GroupBy::class;
    }

    /**
     * 正常系テスト
     */
    public function testGetPhraseString(): void
    {
        $this->assertSame('GROUP BY %s', $this->getNewInstance()->getPhraseString());
    }

    /**
     * メソッド testGetGroupBy のデータプロバイダー
     *
     * @return array
     */
    public function providerGetGroupBy(): array
    {
        return [
            [ [ 'a', 'b', 'c' ], new Columns([ 'a', 'b', 'c' ]) ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetGroupBy
     *
     * @param array    $expected   期待値
     * @param IColumns $prop_value プロパティ group_by_list の値
     */
    public function testGetGroupBy($expected, $prop_value): void
    {
        /** @var GroupBy $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_GROUP_BY_LIST)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getGroupBy()->getColumns());
    }

    /**
     * メソッド testSetGroupBy のデータプロバイダー
     *
     * @return array
     */
    public function providerSetGroupBy(): array
    {
        return [
            [ [ 'a', 'b', 'c' ], null, new Columns([ 'a', 'b', 'c' ]) ],
            [ [ 'a', 'b', 'c' ], new Columns([ 'x', 'y', 'z' ]), new Columns([ 'a', 'b', 'c' ]) ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetGroupBy
     *
     * @param array    $expected   期待値
     * @param mixed    $prop_value プロパティ group_by_list の値
     * @param IColumns $group_by   メソッド setGroupBy の引数 group_by に渡す値
     */
    public function testSetGroupBy($expected, $prop_value, $group_by): void
    {
        /** @var GroupBy $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_GROUP_BY_LIST);
        $reflector->setValue($object, $prop_value);
        $object->setGroupBy($group_by);

        $this->assertSame($expected, $reflector->getValue($object)->getColumns());
    }

    /**
     * メソッド testSetGroupByFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetGroupByFailure(): array
    {
        $valid_columns = new Columns([ 'x', 'y', 'z' ]);
        $empty_columns = new Columns();
        $order_columns = new Columns([ new Order('col_name') ]);
        $alias_columns = new Columns([ new Alias('orig_table', 'as_table') ]);

        return [
            [ \InvalidArgumentException::class, null, $empty_columns ],
            [ \InvalidArgumentException::class, null, $order_columns ],
            [ \InvalidArgumentException::class, null, $alias_columns ],
            [ \InvalidArgumentException::class, $valid_columns, $empty_columns ],
            [ \InvalidArgumentException::class, $valid_columns, $order_columns ],
            [ \InvalidArgumentException::class, $valid_columns, $alias_columns ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetGroupByFailure
     *
     * @param \Exception $expected   期待値
     * @param mixed      $prop_value プロパティ group_by_list の値
     * @param IColumns   $group_by   メソッド setGroupBy の引数 group_by に渡す値
     */
    public function testSetGroupByFailure($expected, $prop_value, $group_by): void
    {
        $this->expectException($expected);

        /** @var GroupBy $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_GROUP_BY_LIST);
        $reflector->setValue($object, $prop_value);
        $object->setGroupBy($group_by);
    }

    /**
     * メソッド testGetHaving のデータプロバイダー
     *
     * @return array
     */
    public function providerGetHaving(): array
    {
        $condition_1 = (new Conditions())->addCondition('a = 1');
        $condition_2 = (new Conditions())->setConditions('a = 1', 'b = 1');
        $condition_3 = (new Conditions())->addCondition('a = 1')->addCondition('b = 1');

        return [
            [ null, null ],
            [ $condition_1, $condition_1 ],
            [ $condition_2, $condition_2 ],
            [ $condition_3, $condition_3 ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetHaving
     *
     * @param mixed $expected   期待値
     * @param mixed $prop_value プロパティ having_cond の値
     */
    public function testGetHaving($expected, $prop_value): void
    {
        /** @var GroupBy $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_HAVING_COND)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getHaving());
    }

    /**
     * メソッド testSetHaving のデータプロバイダー
     *
     * @return array
     */
    public function providerSetHaving(): array
    {
        $base_condition = (new Conditions())->addCondition('a = 1');

        return [
            [ [ 'a = 1' ], null, (new Conditions())->addCondition('a = 1') ],
            [ [ 'a = 1', 'b = 1' ], null, (new Conditions())->setConditions('a = 1', 'b = 1') ],
            [ [ 'b = 1' ], $base_condition, (new Conditions())->addCondition('b = 1') ],
            [ [ 'b = 1', 'c = 1' ], $base_condition, (new Conditions())->setConditions('b = 1', 'c = 1') ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetHaving
     *
     * @param array      $expected   期待値
     * @param mixed      $prop_value プロパティ having_cond の値
     * @param ICondition $having     メソッド setHaving の引数 conditions の値
     */
    public function testSetHaving($expected, $prop_value, $having): void
    {
        /** @var GroupBy $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_HAVING_COND);
        $reflector->setValue($object, $prop_value);
        $object->setHaving($having);

        $this->assertSame($expected, $reflector->getValue($object)->getConditions());
    }

    /**
     * メソッド testSetHavingFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetHavingFailure(): array
    {
        return [
            [ \InvalidArgumentException::class, null, new Conditions() ],
            [ \InvalidArgumentException::class, new Conditions(), new Conditions() ],
            [ \InvalidArgumentException::class, (new Conditions())->addCondition('a = 1'), new Conditions() ],
            [ \InvalidArgumentException::class, (new Conditions())->setConditions('x = 1', 'y = 1'), new Conditions() ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetHavingFailure
     *
     * @param \Exception $expected   期待値
     * @param mixed      $prop_value プロパティ having_cond の値
     * @param ICondition $having     メソッド setHaving の引数 conditions の値
     */
    public function testSetHavingFailure($expected, $prop_value, $having): void
    {
        $this->expectException($expected);

        /** @var GroupBy $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_HAVING_COND)->setValue($object, $prop_value);
        $object->setHaving($having);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        return [
            [ 'GROUP BY a', new Columns([ 'a' ]), null ],
            [
                'GROUP BY a HAVING a > 0',
                new Columns([ 'a' ]),
                (new Conditions())->addCondition('a > 0')
            ],
            [
                'GROUP BY a HAVING a > 0 AND b > 0',
                new Columns([ 'a' ]),
                (new Conditions())->setConditions('a > 0', 'b > 0')
            ],
            [
                'GROUP BY a, b HAVING a > 0 OR b > 0',
                new Columns([ 'a', 'b' ]),
                (new Conditions(Conditions::OPERATION_OR))->setConditions('a > 0', 'b > 0')
            ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string   $expected 期待値
     * @param IColumns $group_by コンストラクタの引数 group_by に渡す値
     * @param mixed    $having   コンストラクタの引数 having に渡す値
     */
    public function testToString($expected, $group_by, $having): void
    {
        $this->assertSame($expected, (string)new GroupBy($group_by, $having));
    }
}
