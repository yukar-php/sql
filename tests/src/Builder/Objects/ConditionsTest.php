<?php
namespace Yukar\Sql\Tests\Builder\Objects;

use Yukar\Sql\Builder\Objects\Conditions;
use Yukar\Sql\Builder\Operators\AtCondition\Between;
use Yukar\Sql\Builder\Operators\AtCondition\Expression;
use Yukar\Sql\Builder\Operators\AtCondition\IsNull;
use Yukar\Sql\Builder\Operators\AtCondition\Like;

/**
 * クラス Conditions の単体テスト
 *
 * @author hiroki sugawara
 */
class ConditionsTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_OPERATOR = 'operator';
    const PROP_NAME_CONDITIONS = 'conditions';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Conditions コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Conditions
    {
        /** @var Conditions $instance */
        $instance = (new \ReflectionClass(Conditions::class))->newInstanceWithoutConstructor();

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
     * testGetOperation のデータプロバイダー
     *
     * @return array
     */
    public function providerGetOperation()
    {
        return [
            [ 'AND', Conditions::OPERATORS[1] ],
            [ 'AND', Conditions::OPERATORS[Conditions::OPERATION_AND] ],
            [ 'OR', Conditions::OPERATORS[2] ],
            [ 'OR', Conditions::OPERATORS[Conditions::OPERATION_OR] ],
            [ 'AND', null ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetOperation
     *
     * @param string $expected 期待値
     * @param int $prop_value  プロパティ operator の値
     */
    public function testGetOperation($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_OPERATOR)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getOperation());
    }

    /**
     * providerSetOperation のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOperation()
    {
        return [
            [ 'AND', 1 ],
            [ 'AND', Conditions::OPERATION_AND ],
            [ 'OR', 2 ],
            [ 'OR', Conditions::OPERATION_OR ],
            [ 'AND', -1 ],
            [ 'AND', 3 ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetOperation
     *
     * @param string $expected 期待値
     * @param int $prop_value  プロパティ operator の値
     */
    public function testSetOperation($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $property = $this->getProperty($object, self::PROP_NAME_OPERATOR);
        $object->setOperation($prop_value);

        self::assertSame($expected, $property->getValue($object));
    }

    /**
     * testGetConditions のデータプロバイダー
     *
     * @return array
     */
    public function providerGetConditions()
    {
        return [
            [ [], [] ],
            [ [ 'a = 1' ], [ 'a = 1' ] ],
            [ [ 'a = 1', 'b = 2' ], [ 'a = 1', 'b = 2' ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetConditions
     *
     * @param array $expected   期待値
     * @param array $conditions プロパティ conditions の値
     */
    public function testGetConditions($expected, $conditions)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_CONDITIONS)->setValue($object, $conditions);

        self::assertSame($expected, $object->getConditions());
    }

    /**
     * testAddConditions のデータプロバイダー
     *
     * @return array
     */
    public function providerAddConditions()
    {
        $condition = new Conditions(Conditions::OPERATION_AND);
        $condition->setConditions('sa = 1', 'sb = 2');

        return [
            [ [ 'a = 1' ], [], 'a = 1' ],
            [ [ $condition ], [], $condition ],
            [ [ 'x = 1', 'y = 2' ], [ 'x = 1' ], 'y = 2' ],
            [ [ 'x = 1', $condition ], [ 'x = 1' ], $condition ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerAddConditions
     *
     * @param array $expected             期待値
     * @param array $base_cond            プロパティ conditions の値
     * @param string|Conditions $add_cond メソッド addCondition の引数として渡す値
     */
    public function testAddConditions($expected, $base_cond, $add_cond)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_CONDITIONS);

        $reflector->setValue($object, $base_cond);
        $object->addCondition($add_cond);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * testAddConditionsFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerAddConditionsFailure()
    {
        $condition = new Conditions(Conditions::OPERATION_AND);
        $condition->setConditions('sa = 1', 'sb = 2');

        return [
            // 許されない型の値を指定した場合
            [ '\TypeError', [], 1 ],
            [ '\TypeError', [], true ],
            [ '\TypeError', [], null ],
            [ '\TypeError', [], new \stdClass() ],
            // 既に条件数が上限に達している場合
            [ '\OverflowException', [ 'a = 1', 'b = 1' ], 'c = 1' ],
            [ '\OverflowException', [ 'a = 1', 'b = 1' ], $condition ],
            // 既に条件数が上限に達しており、許されない型を指定した場合
            [ '\TypeError', [ 'x = 1', 'y = 2' ], -1 ],
            [ '\TypeError', [ 'x = 1', 'y = 2' ], false ],
            [ '\TypeError', [ 'x = 1', 'y = 2' ], null ],
            [ '\TypeError', [ 'x = 1', 'y = 2' ], new \stdClass() ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerAddConditionsFailure
     *
     * @param \Exception $expected 期待する例外
     * @param array $base_cond    プロパティ conditions の値
     * @param mixed $add_cond     メソッド addCondition の引数として渡す値
     */
    public function testAddConditionsFailure($expected, $base_cond, $add_cond)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_CONDITIONS)->setValue($object, $base_cond);
        $object->addCondition($add_cond);
    }

    /**
     * testSetConditions のデータプロバイダー
     *
     * @return array
     */
    public function providerSetConditions()
    {
        return [
            [ [ 'a = 1', 'b = 2' ], [], 'a = 1', 'b = 2' ],
            [ [ 'a = 1', 'b = 2' ], [ 'x = 1', 'y = 2' ], 'a = 1', 'b = 2' ]
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetConditions
     *
     * @param array $expected           期待値
     * @param array $base_cond          プロパティ conditions の値
     * @param string|Conditions $first  メソッド setConditions の引数 first に渡す値
     * @param string|Conditions $second メソッド setConditions の引数 second に渡す値
     */
    public function testSetConditions($expected, $base_cond, $first, $second)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_CONDITIONS);
        $reflector->setValue($object, $base_cond);
        $object->setConditions($first, $second);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * testSetConditionsFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetConditionsFailure()
    {
        $condition = new Conditions(Conditions::OPERATION_AND);
        $condition->setConditions('sa = 1', 'sb = 2');

        return [
            [ '\TypeError', [], 1, 'right = 1' ],
            [ '\TypeError', [], -1, $condition ],
            [ '\TypeError', [], 'left = 1', null ],
            [ '\TypeError', [], $condition, true ],
            [ '\TypeError', [], false, new \stdClass() ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetConditionsFailure
     *
     * @param |Exception $expected  期待する例外
     * @param array $base_cond プロパティ conditions の値
     * @param mixed $first     メソッド setConditions の引数 first に渡す値
     * @param mixed $second    メソッド setConditions の引数 second に渡す値
     */
    public function testSetConditionsFailure($expected, $base_cond, $first, $second)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_CONDITIONS)->setValue($object, $base_cond);
        $object->setConditions($first, $second);
    }

    /**
     * testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        $condition = new Conditions(Conditions::OPERATION_AND);
        $condition->setConditions('sa = 1', new Expression('sb', 2));
        $condition_2 = new Conditions(Conditions::OPERATION_OR);
        $condition_2->setConditions('oa = 1', $condition);

        return [
            // 通常
            [ 'a = 1 AND b = 2', Conditions::OPERATION_AND, 'a = 1', 'b = 2' ],
            [ 'x = 1 OR y = 2', Conditions::OPERATION_OR, 'x = 1', 'y = 2' ],
            // 片方入れ子
            [ 'a = 1 AND (sa = 1 AND sb = 2)', Conditions::OPERATION_AND, 'a = 1', $condition ],
            [ '(sa = 1 AND sb = 2) AND a = 1', Conditions::OPERATION_AND, $condition, 'a = 1' ],
            [ 'o = 1 OR (sa = 1 AND sb = 2)', Conditions::OPERATION_OR, 'o = 1', $condition ],
            [ '(sa = 1 AND sb = 2) OR o = 1', Conditions::OPERATION_OR, $condition, 'o = 1' ],
            // 両方入れ子
            [ '(sa = 1 AND sb = 2) AND (sa = 1 AND sb = 2)', Conditions::OPERATION_AND, $condition, $condition ],
            [ '(sa = 1 AND sb = 2) OR (sa = 1 AND sb = 2)', Conditions::OPERATION_OR, $condition, $condition ],
            // 二重入れ子
            [ 's = 1 AND (oa = 1 OR (sa = 1 AND sb = 2))', Conditions::OPERATION_AND, 's = 1', $condition_2 ],
            [ '(oa = 1 OR (sa = 1 AND sb = 2)) AND s = 1', Conditions::OPERATION_AND, $condition_2, 's = 1' ],
            [ 's = 1 OR (oa = 1 OR (sa = 1 AND sb = 2))', Conditions::OPERATION_OR, 's = 1', $condition_2 ],
            [ '(oa = 1 OR (sa = 1 AND sb = 2)) OR s = 1', Conditions::OPERATION_OR, $condition_2, 's = 1' ],
            // 両方入れ子・片方は二重入れ子
            [
                '(sa = 1 AND sb = 2) AND (oa = 1 OR (sa = 1 AND sb = 2))',
                Conditions::OPERATION_AND,
                $condition,
                $condition_2
            ],
            [
                '(oa = 1 OR (sa = 1 AND sb = 2)) OR (sa = 1 AND sb = 2)',
                Conditions::OPERATION_OR,
                $condition_2,
                $condition
            ],
            // 両方入れ子・両方とも二重入れ子
            [
                '(oa = 1 OR (sa = 1 AND sb = 2)) AND (oa = 1 OR (sa = 1 AND sb = 2))',
                Conditions::OPERATION_AND,
                $condition_2,
                $condition_2
            ],
            [
                '(oa = 1 OR (sa = 1 AND sb = 2)) OR (oa = 1 OR (sa = 1 AND sb = 2))',
                Conditions::OPERATION_OR,
                $condition_2,
                $condition_2
            ],
            // 演算子入り
            [
                'a >= 2 AND b <= 5',
                Conditions::OPERATION_AND,
                new Expression('a', 2, Expression::SIGN_AO),
                new Expression('b', 5, Expression::SIGN_OU)
            ],
            [
                'c BETWEEN 10 AND 20 OR d NOT BETWEEN 5 AND 50',
                Conditions::OPERATION_OR,
                new Between('c', 10, 20),
                new Between('d', 5, 50, true)
            ],
            [
                'e IS NULL AND f IS NOT NULL',
                Conditions::OPERATION_AND,
                new IsNull('e'),
                new IsNull('f', true)
            ],
            [
                "g LIKE 'patter%' OR h NOT LIKE '_eed%'",
                Conditions::OPERATION_OR,
                new Like('g', 'patter%'),
                new Like('h', '_eed%', true)
            ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected          期待値
     * @param int $operator             論理演算子
     * @param string|Conditions $first  メソッド setConditions の引数 first に渡す値
     * @param string|Conditions $second メソッド setConditions の引数 second に渡す値
     */
    public function testToString($expected, $operator, $first, $second)
    {
        self::assertSame($expected, (string)(new Conditions($operator))->setConditions($first, $second));
    }
}
