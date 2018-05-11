<?php
namespace Yukar\Sql\Tests\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Between;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Exists;
use Yukar\Sql\Builder\Common\Operators\AtCondition\In;
use Yukar\Sql\Builder\Common\Operators\AtCondition\IsNull;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Like;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Not;
use Yukar\Sql\Builder\Common\Statements\Dml\Select;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Interfaces\Builder\Common\Operators\IDeniableOperator;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス Not の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Operators\AtCondition
 * @author hiroki sugawara
 */
class NotTest extends CustomizedTestCase
{
    private const PROP_NAME_DENIABLE_OPERATOR = 'deniable_operator';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return Not::class;
    }

    /**
     * メソッド testSetNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerGetNameFailure(): array
    {
        return [
            [ \BadMethodCallException::class, 'name' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerGetNameFailure
     *
     * @param |Exception $expected 期待値
     */
    public function testGetNameFailure($expected): void
    {
        $this->expectException($expected);
        $this->getNewInstance()->getName();
    }

    /**
     * メソッド testSetNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetNameFailure(): array
    {
        return [
            [ \BadMethodCallException::class, 'name' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetNameFailure
     *
     * @param |Exception $expected 期待値
     * @param mixed      $name     メソッド setName の引数 name に渡す値
     */
    public function testSetNameFailure($expected, $name): void
    {
        $this->expectException($expected);
        $this->getNewInstance()->setName($name);
    }

    /**
     * メソッド testGetDeniableOperator のデータプロバイダー
     *
     * @return array
     */
    public function providerGetDeniableOperator(): array
    {
        $between = new Between('c', 10, 20);
        $is_null = new IsNull('e');
        $like = new Like('g', 'patter%');
        $in = new In('i', [ 'x', 'y', 'z' ]);
        $exists = new Exists(new Select(new From(new Table('table_k'))));

        return [
            [ $between, $between ],
            [ $is_null, $is_null ],
            [ $like, $like ],
            [ $in, $in ],
            [ $exists, $exists ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetDeniableOperator
     *
     * @param IDeniableOperator $expected   期待値
     * @param IDeniableOperator $prop_value プロパティ deniable_operator の値
     */
    public function testGetDeniableOperator($expected, $prop_value): void
    {
        /** @var Not $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_DENIABLE_OPERATOR)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getDeniableOperator());
    }

    /**
     * メソッド testSetDeniableOperator のデータプロバイダー
     *
     * @return array
     */
    public function providerSetDeniableOperator(): array
    {
        $between = new Between('c', 10, 20);
        $is_null = new IsNull('e');
        $like = new Like('g', 'patter%');
        $in = new In('i', [ 'x', 'y', 'z' ]);
        $exists = new Exists(new Select(new From(new Table('table_k'))));

        return [
            [ $between, null, $between ],
            [ $is_null, null, $is_null ],
            [ $like, null, $like ],
            [ $in, null, $in ],
            [ $exists, null, $exists ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetDeniableOperator
     *
     * @param IDeniableOperator $expected          期待値
     * @param IDeniableOperator $prop_value        プロパティ deniable_operator の値
     * @param IDeniableOperator $deniable_operator メソッド setDeniableOperator の引数 deniable_operator に渡す値
     */
    public function testSetDeniableOperator($expected, $prop_value, $deniable_operator): void
    {
        /** @var Not $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_DENIABLE_OPERATOR);
        $reflector->setValue($object, $prop_value);
        $object->setDeniableOperator($deniable_operator);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        $between = new Between('c', 10, 20);
        $is_null = new IsNull('e');
        $like = new Like('g', 'patter%');
        $in = new In('i', [ 'x', 'y', 'z' ]);
        $exists = new Exists(new Select(new From(new Table('table_k'))));

        return [
            [ 'c NOT BETWEEN 10 AND 20', $between ],
            [ 'e IS NOT NULL', $is_null ],
            [ 'g NOT LIKE \'patter%\'', $like ],
            [ 'i NOT IN (\'x\', \'y\', \'z\')', $in ],
            [ 'NOT EXISTS (SELECT * FROM table_k)', $exists ]
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string            $expected          期待値
     * @param IDeniableOperator $deniable_operator コンストラクタの引数 deniable_operator に渡す値
     */
    public function testToString($expected, $deniable_operator): void
    {
        $this->assertSame($expected, (string)new Not($deniable_operator));
    }
}
