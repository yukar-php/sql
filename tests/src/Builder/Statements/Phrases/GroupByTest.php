<?php
namespace Yukar\Sql\Tests\Builder\Statements\Phrases;

use Yukar\Sql\Builder\Objects\Columns;
use Yukar\Sql\Builder\Objects\Conditions;
use Yukar\Sql\Builder\Statements\Phrases\GroupBy;

/**
 * クラス GroupBy の単体テスト
 *
 * @author hiroki sugawara
 */
class GroupByTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_GROUP_BY_LIST = 'group_by_list';
    const PROP_NAME_HAVING_COND = 'having_cond';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return GroupBy コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): GroupBy
    {
        /** @var GroupBy $instance */
        $instance = (new \ReflectionClass(GroupBy::class))->newInstanceWithoutConstructor();

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
        $property = (new \ReflectionClass($object))->getProperty($property_name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * 正常系テスト
     */
    public function testGetPhraseString()
    {
        self::assertSame('GROUP BY %s', $this->getNewInstance()->getPhraseString());
    }

    /**
     * メソッド testGetGroupBy のデータプロバイダー
     *
     * @return array
     */
    public function providerGetGroupBy()
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
     * @param array $expected     期待値
     * @param Columns $prop_value プロパティ group_by_list の値
     */
    public function testGetGroupBy($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_GROUP_BY_LIST)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getGroupBy()->getColumns());
    }

    /**
     * メソッド testSetGroupBy のデータプロバイダー
     *
     * @return array
     */
    public function providerSetGroupBy()
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
     * @param array $expected   期待値
     * @param mixed $prop_value プロパティ group_by_list の値
     * @param Columns $group_by メソッド setGroupBy の引数 group_by に渡す値
     */
    public function testSetGroupBy($expected, $prop_value, $group_by)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_GROUP_BY_LIST);
        $reflector->setValue($object, $prop_value);
        $object->setGroupBy($group_by);

        self::assertSame($expected, $reflector->getValue($object)->getColumns());
    }

    /**
     * メソッド testSetGroupByFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetGroupByFailure()
    {
        return [
            [ '\InvalidArgumentException', null, new Columns() ],
            [ '\InvalidArgumentException', new Columns([ 'x', 'y', 'z' ]), new Columns() ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetGroupByFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ group_by_list の値
     * @param Columns $group_by    メソッド setGroupBy の引数 group_by に渡す値
     */
    public function testSetGroupByFailure($expected, $prop_value, $group_by)
    {
        $this->expectException($expected);

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
    public function providerGetHaving()
    {
        return [
            [ null, null ],
            [ [ 'a = 1' ], (new Conditions())->addCondition('a = 1') ],
            [ [ 'a = 1', 'b = 1' ], (new Conditions())->setConditions('a = 1', 'b = 1') ],
            [ [ 'a = 1', 'b = 1' ], (new Conditions())->addCondition('a = 1')->addCondition('b = 1') ],
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
    public function testGetHaving($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_HAVING_COND)->setValue($object, $prop_value);
        $having = $object->getHaving();

        self::assertSame($expected, empty($having) ? $having : $having->getConditions());
    }

    /**
     * メソッド testSetHaving のデータプロバイダー
     *
     * @return array
     */
    public function providerSetHaving()
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
     * @param array $expected    期待値
     * @param mixed $prop_value  プロパティ having_cond の値
     * @param Conditions $having メソッド setHaving の引数 conditions の値
     */
    public function testSetHaving($expected, $prop_value, $having)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_HAVING_COND);
        $reflector->setValue($object, $prop_value);
        $object->setHaving($having);

        self::assertSame($expected, $reflector->getValue($object)->getConditions());
    }

    /**
     * メソッド testSetHavingFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetHavingFailure()
    {
        return [
            [ '\InvalidArgumentException', null, new Conditions() ],
            [ '\InvalidArgumentException', new Conditions(), new Conditions() ],
            [ '\InvalidArgumentException', (new Conditions())->addCondition('a = 1'), new Conditions() ],
            [ '\InvalidArgumentException', (new Conditions())->setConditions('x = 1', 'y = 1'), new Conditions() ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetHavingFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ having_cond の値
     * @param Conditions $having   メソッド setHaving の引数 conditions の値
     */
    public function testSetHavingFailure($expected, $prop_value, $having)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_HAVING_COND)->setValue($object, $prop_value);
        $object->setHaving($having);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
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
     * @param string $expected  期待値
     * @param Columns $group_by コンストラクタの引数 group_by に渡す値
     * @param mixed $having     コンストラクタの引数 having に渡す値
     */
    public function testToString($expected, $group_by, $having)
    {
        self::assertSame($expected, (string)new GroupBy($group_by, $having));
    }
}
