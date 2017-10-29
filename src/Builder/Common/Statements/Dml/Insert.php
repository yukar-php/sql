<?php
namespace Yukar\Sql\Builder\Statements\Dml;

use Yukar\Sql\Builder\Statements\Phrases\Into;
use Yukar\Sql\Builder\Statements\Phrases\Values;
use Yukar\Sql\Interfaces\Builder\Objects\ISqlQuerySource;
use Yukar\Sql\Interfaces\Builder\Statements\IInsertQuery;
use Yukar\Sql\Interfaces\Builder\Statements\ISelectQuery;

/**
 * テーブルへデータを追加する挿入の問い合わせクエリの機能を提供します。
 *
 * @author hiroki sugawara
 */
class Insert extends BaseSqlDMLQuery implements IInsertQuery
{
    private $values;

    /**
     * Insert クラスの新しいインスタンスを初期化します。
     *
     * @param Into $into                  挿入の問い合わせクエリの対象となる表と列
     * @param Values|ISelectQuery $values 挿入の問い合わせクエリの対象となる値のリストまたはサブクエリ
     */
    public function __construct(Into $into, $values)
    {
        $this->setSqlQuerySource($into);
        $this->setValues($values);
    }

    /**
     * SQLのデータ操作言語の問い合わせクエリの書式を取得します。
     *
     * @return string SQLのデータ操作言語の問い合わせクエリの書式
     */
    public function getQueryFormat(): string
    {
        return 'INSERT %s %s';
    }

    /**
     * SQLのデータ操作言語の対象となる表やサブクエリを設定します。
     *
     * @param ISqlQuerySource $sql_query_source SQLのデータ操作言語の対象となる表やサブクエリ
     */
    public function setSqlQuerySource(ISqlQuerySource $sql_query_source)
    {
        if ($sql_query_source instanceof Into === false) {
            throw new \InvalidArgumentException();
        }

        parent::setSqlQuerySource($sql_query_source);
    }

    /**
     * 挿入の問い合わせクエリの対象となる値のリストまたはサブクエリを取得します。
     *
     * @return Values|ISelectQuery 挿入の問い合わせクエリの対象となる値のリストまたはサブクエリ
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * 挿入の問い合わせクエリの対象となる値のリストまたはサブクエリを設定します。
     *
     * @param Values|ISelectQuery $values 挿入の問い合わせクエリの対象となる値のリストまたはサブクエリ
     *
     * @throws \InvalidArgumentException 引数 values に許容できない型の値が渡された場合
     */
    public function setValues($values)
    {
        if ($this->isAcceptableValues($values) === false) {
            throw new \InvalidArgumentException();
        }

        $this->values = $values;
    }

    /**
     * SQLのデータ操作言語の問い合わせクエリを文字列として取得します。
     *
     * @return string SQLのデータ操作言語の問い合わせクエリ
     */
    public function __toString(): string
    {
        return sprintf($this->getQueryFormat(), $this->getSqlQuerySource(), $this->getValues());
    }

    /**
     * VALUES 句または SELECT 句に含めることができる値かどうかを判別します。
     *
     * @param mixed $values 判別対象の値
     *
     * @return bool VALUES 句または SELECT 句に含めることができる場合は true。それ以外の場合は false。
     */
    private function isAcceptableValues($values): bool
    {
        return ($values instanceof Values === true || $values instanceof ISelectQuery === true);
    }
}
