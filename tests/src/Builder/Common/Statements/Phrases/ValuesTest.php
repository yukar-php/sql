<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Phrases;

use Yukar\Sql\Builder\Common\Statements\Phrases\Values;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス Values の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Statements\Phrases
 * @author hiroki sugawara
 */
class ValuesTest extends CustomizedTestCase
{
    private const PROP_NAME_VALUES = 'values';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return Values::class;
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
        /** @var Values $object */
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
        /** @var Values $object */
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

        /** @var Values $object */
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
