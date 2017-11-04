<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Phrases;

use Yukar\Sql\Builder\Common\Statements\Phrases\Values;

/**
 * クラス Values の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Statements\Phrases
 * @author hiroki sugawara
 */
class ValuesTest extends \PHPUnit_Framework_TestCase
{
    private const PROP_NAME_VALUES = 'values';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Values コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Values
    {
        /** @var Values $instance */
        $instance = (new \ReflectionClass(Values::class))->newInstanceWithoutConstructor();

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
    public function testGetPhraseString(): void
    {
        $this->assertSame('VALUES (%s)', $this->getNewInstance()->getPhraseString());
    }

    /**
     * メソッド testGetValues のデータプロバイダー
     *
     * @return array
     */
    public function providerGetValues(): array
    {
        return [
            [ [ [ 'a', 'b', 'c' ] ], [ [ 'a', 'b', 'c' ] ] ],
            [ [ [ 'a', 'b', 'c' ] ], new \ArrayObject([ [ 'a', 'b', 'c' ] ]) ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetValues
     *
     * @param array $expected   期待値
     * @param array $prop_value プロパティ values の値
     */
    public function testGetValues($expected, $prop_value): void
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_VALUES)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getValues());
    }

    /**
     * メソッド testSetValues のデータプロバイダー
     *
     * @return array
     */
    public function providerSetValues(): array
    {
        $array_obj_1 = new \ArrayObject([ [ 'a', 'b', 'c' ] ]);
        $array_obj_2 = new \ArrayObject([ [ 'x', 'y', 'z' ] ]);

        return [
            [ [ [ 'a', 'b', 'c' ] ], null, [ [ 'a', 'b', 'c' ] ] ],
            [ $array_obj_1, null, $array_obj_1 ],
            [ [ [ 'x', 'y', 'z' ] ], $array_obj_1, [ [ 'x', 'y', 'z' ] ] ],
            [ $array_obj_2, [ [ 'a', 'b', 'c' ] ],$array_obj_2 ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetValues
     *
     * @param array    $expected   期待値
     * @param mixed    $prop_value プロパティ values の値
     * @param iterable $values     メソッド setValues の引数 values に渡す値
     */
    public function testSetValues($expected, $prop_value, $values): void
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_VALUES);
        $reflector->setValue($object, $prop_value);
        $object->setValues($values);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetValuesFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetValuesFailure(): array
    {
        return [
            // 初回設定時
            [ \TypeError::class, null, null ],
            [ \TypeError::class, null, 0 ],
            [ \TypeError::class, null, 100 ],
            [ \TypeError::class, null, 0.1 ],
            [ \TypeError::class, null, true ],
            [ \TypeError::class, null, '' ],
            [ \TypeError::class, null, '2' ],
            [ \TypeError::class, null, 'abc' ],
            [ \InvalidArgumentException::class, null, [] ],
            [ \InvalidArgumentException::class, null, new \ArrayObject() ],
            [ \TypeError::class, null, new \stdClass() ],
            // 二回目以降の設定時
            [ \TypeError::class, [ [ 'a', 'b' ] ], null ],
            [ \TypeError::class, [ [ 'a', 'b' ] ], 1 ],
            [ \TypeError::class, [ [ 'a', 'b' ] ], 1.2 ],
            [ \TypeError::class, [ [ 'a', 'b' ] ], false ],
            [ \TypeError::class, [ [ 'a', 'b' ] ], '' ],
            [ \TypeError::class, [ [ 'a', 'b' ] ], '3' ],
            [ \TypeError::class, [ [ 'a', 'b' ] ], 'xyz' ],
            [ \InvalidArgumentException::class, [ [ 'a', 'b' ] ], [] ],
            [ \InvalidArgumentException::class, [ [ 'a', 'b' ] ], new \ArrayObject() ],
            [ \TypeError::class, [ [ 'a', 'b' ] ], new \stdClass() ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetValuesFailure
     *
     * @param \Exception $expected   期待値
     * @param mixed      $prop_value プロパティ values の値
     * @param mixed      $values     メソッド setValues の引数 values に渡す値
     */
    public function testSetValuesFailure($expected, $prop_value, $values): void
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_VALUES)->setValue($object, $prop_value);
        $object->setValues($values);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        return [
            [ "VALUES ('a', 'b', 'c')", [ [ 'a', 'b', 'c' ] ] ],
            [ "VALUES (1, 2, 3)", [ [ 1, 2, 3 ] ] ],
            [ "VALUES ('a', 'b', 'c')", [ new \ArrayObject([ 'a', 'b', 'c' ]) ] ],
            [ "VALUES (1, 2, 3)", [ new \ArrayObject([ 1, 2, 3 ]) ] ],
            [ "VALUES ('a', 'b', 'c')", new \ArrayObject([ [ 'a', 'b', 'c' ] ]) ],
            [ "VALUES (1, 2, 3)", new \ArrayObject([ [ 1, 2, 3 ] ])],
            [ "VALUES ('a', 'b', 'c'), ('d', 'e', 'f')", [ [ 'a', 'b', 'c' ], [ 'd', 'e', 'f' ] ] ],
            [ "VALUES (1, 2, 3), (4, 5, 6)", [ [ 1, 2, 3 ], [ 4, 5, 6 ] ] ],
            [
                "VALUES ('a', 'b', 'c'), ('d', 'e', 'f')",
                [ new \ArrayObject([ 'a', 'b', 'c' ]), new \ArrayObject([ 'd', 'e', 'f' ]) ]
            ],
            [
                "VALUES (1, 2, 3), (4, 5, 6)", [ new \ArrayObject([ 1, 2, 3 ]), new \ArrayObject([ 4, 5, 6 ]) ]
            ],
            [
                "VALUES ('a', 'b', 'c'), ('d', 'e', 'f')",
                new \ArrayObject([ [ 'a', 'b', 'c' ], [ 'd', 'e', 'f' ] ])
            ],
            [
                "VALUES (1, 2, 3), (4, 5, 6)", new \ArrayObject([ [ 1, 2, 3 ], [ 4, 5, 6 ] ])
            ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string   $expected 期待値
     * @param iterable $values   コンストラクタ values に渡す値
     */
    public function testToString($expected, $values): void
    {
        $this->assertSame($expected, (string)new Values($values));
    }
}
