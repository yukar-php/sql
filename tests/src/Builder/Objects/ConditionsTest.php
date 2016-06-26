<?php
namespace Yukar\Sql\Tests\Builder\Objects;

use Yukar\Sql\Builder\Objects\Conditions;

/**
 * クラス Conditions の単体テスト
 *
 * @author hiroki sugawara
 */
class ConditionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testGetOperation のデータプロバイダー
     *
     * @return array
     */
    public function providerGetOperation()
    {
        $hash = [ Conditions::OPERATION_AND => 'AND', Conditions::OPERATION_OR => 'OR' ];

        return [
            [ 'AND', $hash[1] ],
            [ 'AND', $hash[Conditions::OPERATION_AND] ],
            [ 'OR', $hash[2] ],
            [ 'OR', $hash[Conditions::OPERATION_OR] ],
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
        $object = $this->getConditionsInstance();
        $this->getOperatorProperty(new \ReflectionClass($object))->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getOperation());
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
        $object = $this->getConditionsInstance();
        $property = $this->getOperatorProperty(new \ReflectionClass($object));
        $object->setOperation($prop_value);

        $this->assertSame($expected, $property->getValue($object));
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
        $object = $this->getConditionsInstance();
        $this->getConditionsProperty(new \ReflectionClass($object))->setValue($object, $conditions);

        $this->assertSame($expected, $object->getConditions());
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
        $object = $this->getConditionsInstance();
        $reflector = $this->getConditionsProperty(new \ReflectionClass($object));

        $reflector->setValue($object, $base_cond);
        $object->addCondition($add_cond);

        $this->assertSame($expected, $reflector->getValue($object));
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

        $object = $this->getConditionsInstance();
        $this->getConditionsProperty(new \ReflectionClass($object))->setValue($object, $base_cond);
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
        $object = $this->getConditionsInstance();
        $reflector = $this->getConditionsProperty(new \ReflectionClass($object));
        $reflector->setValue($object, $base_cond);
        $object->setConditions($first, $second);

        $this->assertSame($expected, $reflector->getValue($object));
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

        $object = $this->getConditionsInstance();
        $this->getConditionsProperty(new \ReflectionClass($object))->setValue($object, $base_cond);
        $object->setConditions($first, $second);
    }

    /**
     * コンストラクタを通さずに作成した Conditions クラスの新しいインスタンスを取得します。
     *
     * @return Conditions コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getConditionsInstance(): Conditions
    {
        return (new \ReflectionClass('Yukar\Sql\Builder\Objects\Conditions'))->newInstanceWithoutConstructor();
    }

    /**
     * プロパティ operator のリフレクションを持つインスタンスを取得します。
     *
     * @param \ReflectionClass $reflector クラス Conditions のオブジェクトのリフレクション
     *
     * @return \ReflectionProperty プロパティ operator のリフレクションを持つインスタンス
     */
    private function getOperatorProperty(\ReflectionClass $reflector): \ReflectionProperty
    {
        $property = $reflector->getProperty('operator');
        $property->setAccessible(true);

        return $property;
    }

    /**
     * プロパティ conditions のリフレクションを持つインスタンスを取得します。
     *
     * @param \ReflectionClass $reflector クラス Conditions のオブジェクトのリフレクション
     *
     * @return \ReflectionProperty プロパティ conditions のリフレクションを持つインスタンス
     */
    private function getConditionsProperty(\ReflectionClass $reflector): \ReflectionProperty
    {
        $property = $reflector->getProperty('conditions');
        $property->setAccessible(true);

        return $property;
    }

    /**
     * testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        $condition = new Conditions(Conditions::OPERATION_AND);
        $condition->setConditions('sa = 1', 'sb = 2');
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
        $this->assertSame($expected, (string)(new Conditions($operator))->setConditions($first, $second));
    }
}
