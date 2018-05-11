<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Dml;

use Yukar\Sql\Builder\Common\Objects\Columns;
use Yukar\Sql\Builder\Common\Objects\Conditions;
use Yukar\Sql\Builder\Common\Objects\SetValuesHash;
use Yukar\Sql\Builder\Common\Objects\Table;
use Yukar\Sql\Builder\Common\Operators\Alias;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Exists;
use Yukar\Sql\Builder\Common\Operators\AtCondition\Expression;
use Yukar\Sql\Builder\Common\Statements\Dml\Select;
use Yukar\Sql\Builder\Common\Statements\Dml\Update;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Builder\Common\Statements\Phrases\Into;
use Yukar\Sql\Builder\Common\Statements\Phrases\Set;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ICondition;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ISqlQuerySource;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ITable;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス Update の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Statements\Dml
 * @author hiroki sugawara
 */
class UpdateTest extends CustomizedTestCase
{
    private const PROP_NAME_UPDATE_SETS = 'update_sets';
    private const PROP_NAME_FROM = 'from';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return Update::class;
    }

    /**
     * 正常系テスト
     */
    public function testGetQueryFormat(): void
    {
        $this->assertSame('UPDATE %s %s %s', $this->getNewInstance()->getQueryFormat());
    }

    /**
     * メソッド testSetSqlQuerySourceFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetSqlQuerySourceFailure(): array
    {
        return [
            [ new From(new Table('table_name')) ],
            [ new Into(new Table('table_name')) ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetSqlQuerySourceFailure
     *
     * @param ISqlQuerySource $sql_query_source メソッド setSqlQuerySource の引数 sql_query_source に渡す値
     */
    public function testSetSqlQuerySourceFailure($sql_query_source): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->getNewInstance()->setSqlQuerySource($sql_query_source);
    }

    /**
     * メソッド testGetUpdateSets のデータプロバイダー
     *
     * @return array
     */
    public function providerGetUpdateSets(): array
    {
        $set_1 = new Set(new SetValuesHash([ 'col1' => 'val1' ]));
        $set_2 = new Set(new SetValuesHash([ 'col1' => 'val1', 'col2' => '20' ]));

        return [
            [ $set_1, $set_1 ],
            [ $set_2, $set_2 ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetUpdateSets
     *
     * @param Set $expected   期待値
     * @param Set $prop_value プロパティ update_sets の値
     */
    public function testGetUpdateSets($expected, $prop_value): void
    {
        /** @var Update $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_UPDATE_SETS)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getUpdateSets());
    }

    /**
     * メソッド testSetUpdateSets のデータプロバイダー
     *
     * @return array
     */
    public function providerSetUpdateSets(): array
    {
        $set_1 = new Set(new SetValuesHash([ 'col1' => 'val1' ]));
        $set_2 = new Set(new SetValuesHash([ 'col1' => 'val1', 'col2' => '20' ]));

        return [
            [ $set_1, null, $set_1 ],
            [ $set_2, null, $set_2 ],
            [ $set_1, $set_2, $set_1 ],
            [ $set_2, $set_1, $set_2 ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetUpdateSets
     *
     * @param Set $expected     期待値
     * @param mixed $prop_value プロパティ update_sets の値
     * @param Set $update_sets  メソッド setUpdateSets の引数 update_sets に渡す値
     */
    public function testSetUpdateSets($expected, $prop_value, $update_sets): void
    {
        /** @var Update $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_UPDATE_SETS);
        $reflector->setValue($object, $prop_value);
        $object->setUpdateSets($update_sets);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetWhere のデータプロバイダー
     *
     * @return array
     */
    public function providerSetWhere(): array
    {
        return [
            [ (new Conditions())->addCondition(new Expression('a', 1)) ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetWhere
     *
     * @param ICondition $condition メソッド setWhere の引数 condition に渡す値
     */
    public function testSetWhere($condition): void
    {
        // wrapしているsetWhereメソッドの単体テストは別のところで実施済みなので戻り値のチェックだけをする
        $this->assertInstanceOf(Update::class, $this->getNewInstance()->setWhere($condition));
    }

    /**
     * メソッド testGetFrom のデータプロバイダー
     *
     * @return array
     */
    public function providerGetFrom(): array
    {
        $from_origin = new From(new Table('table_name'));
        $from_alias = new From(new Alias('table_a', 'a'));

        return [
            [ $from_origin, $from_origin ],
            [ $from_alias, $from_alias ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetFrom
     *
     * @param From $expected   期待値
     * @param From $prop_value プロパティ from の値
     */
    public function testGetFrom($expected, $prop_value): void
    {
        /** @var Update $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_FROM)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getFrom());
    }

    /**
     * メソッド testSetFrom のデータプロバイダー
     *
     * @return array
     */
    public function providerSetFrom(): array
    {
        $from_origin = new From(new Table('table_name'));
        $from_alias = new From(new Alias('table_a', 'a'));

        return [
            [ $from_origin, null, $from_origin ],
            [ $from_alias, null, $from_alias ],
            [ $from_origin, $from_alias, $from_origin ],
            [ $from_alias, $from_origin, $from_alias ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetFrom
     *
     * @param From $expected    期待値
     * @param mixed $prop_value プロパティ from の値
     * @param From $from        メソッド setFrom の引数 from に渡す値
     */
    public function testSetFrom($expected, $prop_value, $from): void
    {
        /** @var Update $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_FROM);
        $reflector->setValue($object, $prop_value);

        $this->assertInstanceOf(Update::class, $object->setFrom($from));
        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        $target_table = new Table('target_table');
        $source_table = new Table('source_table');
        $single_sets = new Set(new SetValuesHash([ 'a' => '1' ]));
        $double_sets = new Set(new SetValuesHash([ 'b' => '2', 'c' => '3' ]));
        $condition_1 = (new Conditions())->addCondition(new Expression('d', 0, Expression::SIGN_GT));
        $condition_2 = (new Conditions())->addCondition(new Expression('st.x', 10, Expression::SIGN_LT));
        $source_from = new From($source_table);
        $source_from_as = new From(new Alias($source_table, 'st'));
        $condition_3 = (new Conditions())->addCondition(new Exists(new Select($source_from, new Columns([ 'a' ]))));

        return [
            // 条件なし単一列更新
            [
                'UPDATE target_table SET a = 1',
                $target_table,
                $single_sets,
                null,
                null
            ],
            // 条件なし複数列更新
            [
                'UPDATE target_table SET b = 2, c = 3',
                $target_table,
                $double_sets,
                null,
                null
            ],
            // 条件あり単一列更新
            [
                'UPDATE target_table SET a = 1 WHERE d > 0',
                $target_table,
                $single_sets,
                $condition_1,
                null
            ],
            // 取得元あり複数列更新
            [
                'UPDATE target_table SET b = 2, c = 3 FROM source_table',
                $target_table,
                $double_sets,
                null,
                $source_from
            ],
            // 条件つき取得元あり単一列更新
            [
                'UPDATE target_table SET a = 1 FROM source_table AS st WHERE st.x < 10',
                $target_table,
                $single_sets,
                $condition_2,
                $source_from_as
            ],
            // 選択条件つき取得元あり複数列更新
            [
                'UPDATE target_table SET b = 2, c = 3 FROM source_table WHERE EXISTS (SELECT a FROM source_table)',
                $target_table,
                $double_sets,
                $condition_3,
                $source_from
            ]
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected    期待値
     * @param ITable $data_source コンストラクタの引数 data_source に渡す値
     * @param Set $update_sets    コンストラクタの引数 update_sets に渡す値
     * @param Conditions $where   メソッド setWhere の引数 where に渡す値
     * @param From $from          メソッド setFrom の引数 from に渡す値
     */
    public function testToString($expected, $data_source, $update_sets, $where, $from): void
    {
        $update = new Update($data_source, $update_sets);
        isset($where) && $update->setWhere($where);
        isset($from) && $update->setFrom($from);

        $this->assertSame($expected, (string)$update);
    }
}
