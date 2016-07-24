<?php
namespace Yukar\Sql\Tests\Builder\Operators\AtCondition;

use Yukar\Sql\Builder\Objects\Columns;
use Yukar\Sql\Builder\Objects\Table;
use Yukar\Sql\Builder\Operators\AtCondition\In;
use Yukar\Sql\Builder\Statements\Dml\Select;

/**
 * クラス In の単体テスト
 *
 * @author hiroki sugawara
 */
class InTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_NEEDLE = 'needle';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return In コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): In
    {
        /** @var In $instance */
        $instance = (new \ReflectionClass(In::class))->newInstanceWithoutConstructor();

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
     * メソッド testGetNeedle のデータプロバイダー
     *
     * @return array
     */
    public function providerGetNeedle()
    {
        return [
            [ 'SELECT * FROM table', 'SELECT * FROM table' ],
            [ 'column_a, column_b', 'column_a, column_b' ],
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
        return [
            [ 'SELECT * FROM table', null, 'SELECT * FROM table' ],
            [ 'column_a, column_b', null, [ 'column_a', 'column_b' ] ],
            [ 'column_a, column_b', null, new \ArrayObject([ 'column_a', 'column_b' ]) ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetNeedle
     *
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ needle の値
     * @param mixed $needle     メソッド setNeedle の引数 needle に渡す値
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
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ needle の値
     * @param mixed $needle        メソッド setNeedle の引数 needle に渡す値
     */
    public function testSetNeedleFailure($expected, $prop_value, $needle)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_NEEDLE)->setValue($object, $prop_value);
        $object->setNeedle($needle);
    }

    /**
     * メソッド  のデータプロバイダー
     *
     * @return array
     */
    public function providerSetExpression()
    {
        return [
            [ 'a, b, c', null, [ 'a', 'b', 'c' ] ],
            [ 'x, y, z', null, new \ArrayObject([ 'x', 'y', 'z' ]) ],
            [ 'a, b, c', 'o, p, q', [ 'a', 'b', 'c' ] ],
            [ 'x, y, z', 'o, p, q', new \ArrayObject([ 'x', 'y', 'z' ]) ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetExpression
     *
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ needle の値
     * @param mixed $expression メソッド setExpression の引数 expression に渡す値
     */
    public function testSetExpression($expected, $prop_value, $expression)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_NEEDLE);
        $reflector->setValue($object, $prop_value);

        self::assertInstanceOf(get_class($object), $object->setExpression($expression));
        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetExpressionFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetExpressionFailure()
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
     * @dataProvider providerSetExpressionFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ needle の値
     * @param mixed $expression    メソッド setExpression の引数 expression に渡す値
     */
    public function testSetExpressionFailure($expected, $prop_value, $expression)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_NEEDLE)->setValue($object, $prop_value);
        $object->setExpression($expression);
    }

    /**
     * メソッド testSetSubQuery のデータプロバイダー
     *
     * @return array
     */
    public function providerSetSubQuery()
    {
        $select = new Select(new Table('table'), new Columns([ 'col' ]));

        return [
            [ 'SELECT col FROM table', null, 'SELECT col FROM table' ],
            [ 'SELECT col FROM table', null, $select ],
            [ 'a, b, c', null, 'a, b, c' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetSubQuery
     *
     * @param string $expected  期待値
     * @param mixed $prop_value プロパティ needle の値
     * @param mixed $sub_query  メソッド setSubQuery の引数 sub_query に渡す値
     */
    public function testSetSubQuery($expected, $prop_value, $sub_query)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_NEEDLE);
        $reflector->setValue($object, $prop_value);

        self::assertInstanceOf(get_class($object), $object->setSubQuery($sub_query));
        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetSubQueryFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetSubQueryFailure()
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
     * @dataProvider providerSetSubQueryFailure
     *
     * @param |Exception $expected 期待値
     * @param mixed $prop_value    プロパティ needle の値
     * @param mixed $sub_query     メソッド setSubQuery の引数 sub_query に渡す値
     */
    public function testSetSubQueryFailure($expected, $prop_value, $sub_query)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_NEEDLE)->setValue($object, $prop_value);
        $object->setSubQuery($sub_query);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        $select = new Select(new Table('table'), new Columns([ 'col' ]));

        return [
            [ 'column IN (a, b, c)', 'column', [ 'a', 'b', 'c' ], false ],
            [ 'column IN (x, y, z)', 'column', new \ArrayObject([ 'x', 'y', 'z' ]), false ],
            [ 'column IN (o, p, q)', 'column', 'o, p, q', false ],
            [ 'column IN (SELECT col FROM table)', 'column', 'SELECT col FROM table', false ],
            [ 'column IN (SELECT col FROM table)', 'column', $select, false ],
            [ 'column NOT IN (a, b, c)', 'column', [ 'a', 'b', 'c' ], true ],
            [ 'column NOT IN (x, y, z)', 'column', new \ArrayObject([ 'x', 'y', 'z' ]), true ],
            [ 'column NOT IN (o, p, q)', 'column', 'o, p, q', true ],
            [ 'column NOT IN (SELECT col FROM table)', 'column', 'SELECT col FROM table', true ],
            [ 'column NOT IN (SELECT col FROM table)', 'column', $select, true ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected 期待値
     * @param string $name     コンストラクタの引数 name に渡す値
     * @param mixed $needle    コンストラクタの引数 needle に渡す値
     * @param bool $is_not     コンストラクタの引数 is_not に渡す値
     */
    public function testToString($expected, $name, $needle, $is_not)
    {
        self::assertSame($expected, (string)new In($name, $needle, $is_not));
    }
}
