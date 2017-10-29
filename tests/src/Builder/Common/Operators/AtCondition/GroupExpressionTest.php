<?php
namespace Yukar\Sql\Tests\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Operators\AtCondition\GroupExpression;
use Yukar\Sql\Builder\Common\Statements\Dml\Select;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;

/**
 * クラス GroupExpression の単体テスト
 *
 * @author hiroki sugawara
 */
class GroupExpressionTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_MODIFIER = 'modifier';
    const PROP_NAME_NEEDLE = 'needle';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return GroupExpression コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): GroupExpression
    {
        /** @var GroupExpression $instance */
        $instance = (new \ReflectionClass(GroupExpression::class))->newInstanceWithoutConstructor();

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
     * メソッド testGetModifier のデータプロバイダー
     *
     * @return array
     */
    public function providerGetModifier()
    {
        return [
            [ 'ANY', GroupExpression::ANY_MODIFIER ],
            [ 'SOME', GroupExpression::SOME_MODIFIER ],
            [ 'ALL', GroupExpression::ALL_MODIFIER ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetModifier
     *
     * @param string $expected   期待値
     * @param int    $prop_value プロパティ sign の値
     */
    public function testGetModifier($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_MODIFIER)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getModifier());
    }

    /**
     * メソッド testSetModifier のデータプロバイダー
     *
     * @return array
     */
    public function providerSetModifier()
    {
        return [
            [ 1, null, GroupExpression::ANY_MODIFIER ],
            [ 2, 1, GroupExpression::SOME_MODIFIER ],
            [ 3, 2, GroupExpression::ALL_MODIFIER ],
            [ 1, 3, GroupExpression::ANY_MODIFIER ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetModifier
     *
     * @param string $expected   期待値
     * @param int    $prop_value プロパティ sign の値
     * @param int    $modifier   メソッド setModifier の引数 modifier に渡す値
     */
    public function testSetModifier($expected, $prop_value, $modifier)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_MODIFIER);
        $reflector->setValue($object, $prop_value);
        $object->setModifier($modifier);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetModifierFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetModifierFailure()
    {
        return [
            [ \InvalidArgumentException::class, GroupExpression::ANY_MODIFIER, 0 ],
            [ \InvalidArgumentException::class, GroupExpression::ANY_MODIFIER, 4 ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetModifierFailure
     *
     * @param \Exception $expected   期待値
     * @param int        $prop_value プロパティ sign の値
     * @param int        $modifier   メソッド setModifier の引数 modifier に渡す値
     */
    public function testSetModifierFailure($expected, $prop_value, $modifier)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_MODIFIER)->setValue($object, $prop_value);
        $object->setModifier($modifier);
    }

    /**
     * メソッド testGetNeedle のデータプロバイダー
     *
     * @return array
     */
    public function providerGetNeedle()
    {
        return [
            [ 'SELECT * FROM table', 'SELECT * FROM table' ],
            [ 'SELECT * FROM table_name', new Select(new From(new Table('table_name'))) ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetNeedle
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ needle の値
     */
    public function testGetNeedle($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_NEEDLE)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getNeedle());
    }

    /**
     * メソッド testSetNeedle のデータプロバイダー
     *
     * @return array
     */
    public function providerSetNeedle()
    {
        $select = new Select(new From(new Table('table_name')));

        return [
            [ 'SELECT * FROM table', null, 'SELECT * FROM table' ],
            [ $select, 'SELECT * FROM table', $select ],
            [ 'SELECT * FROM table', $select, 'SELECT * FROM table' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetNeedle
     *
     * @param string $expected   期待値
     * @param mixed  $prop_value プロパティ needle の値
     * @param mixed  $needle     メソッド setNeedle の引数 needle に渡す値
     */
    public function testSetNeedle($expected, $prop_value, $needle)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_NEEDLE);
        $reflector->setValue($object, $prop_value);
        $object->setNeedle($needle);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetNeedleFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetNeedleFailure()
    {
        return [
            [ \InvalidArgumentException::class, null, null ],
            [ \InvalidArgumentException::class, null, 0 ],
            [ \InvalidArgumentException::class, null, 1.1 ],
            [ \InvalidArgumentException::class, null, true ],
            [ \InvalidArgumentException::class, null, '' ],
            [ \InvalidArgumentException::class, null, [] ],
            [ \InvalidArgumentException::class, null, new \stdClass() ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetNeedleFailure
     *
     * @param \Exception $expected   期待値
     * @param mixed      $prop_value プロパティ needle の値
     * @param mixed      $needle     メソッド setNeedle の引数 needle に渡す値
     */
    public function testSetNeedleFailure($expected, $prop_value, $needle)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_NEEDLE)->setValue($object, $prop_value);
        $object->setNeedle($needle);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        $select = new Select(new From(new Table('table')));

        return [
            [
                'column >= ANY (SELECT * FROM table)',
                'column',
                $select,
                GroupExpression::ANY_MODIFIER,
                GroupExpression::SIGN_AO
            ],
            [
                'column <> SOME (SELECT * FROM table)',
                'column',
                $select,
                GroupExpression::SOME_MODIFIER,
                GroupExpression::SIGN_NE
            ],
            [
                'column = ALL (SELECT * FROM table)',
                'column',
                $select,
                GroupExpression::ALL_MODIFIER,
                GroupExpression::SIGN_EQ
            ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected 期待値
     * @param string $name     コンストラクタの引数 name に渡す値
     * @param string $needle   コンストラクタの引数 needle に渡す値
     * @param int    $modifier コンストラクタの引数 modifier に渡す値
     * @param int    $sign     コンストラクタの引数 sign に渡す値
     */
    public function testToString($expected, $name, $needle, $modifier, $sign)
    {
        self::assertSame($expected, (string)(new GroupExpression($name, $needle, $modifier, $sign)));
    }
}
