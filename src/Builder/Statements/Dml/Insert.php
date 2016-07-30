<?php
namespace Yukar\Sql\Builder\Statements\Dml;

use Yukar\Sql\Builder\Statements\Phrases\Into;
use Yukar\Sql\Builder\Statements\Phrases\Values;
use Yukar\Sql\Interfaces\Builder\Statements\ISelectQuery;

/**
 * テーブルへデータを追加する挿入の問い合わせクエリの機能を提供します。
 *
 * @author hiroki sugawara
 */
class Insert extends BaseSqlDMLQuery
{
    private $into;
    private $values;

    /**
     * Insert クラスの新しいインスタンスを初期化します。
     *
     * @param Into $into                  挿入の問い合わせクエリの対象となる表と列
     * @param Values|ISelectQuery $values 挿入の問い合わせクエリの対象となる値のリストまたはサブクエリ
     */
    public function __construct(Into $into, $values)
    {
        $this->setDataSource($into->getDataSource());
        $this->setInto($into);
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
     * 挿入の問い合わせクエリの対象となる表と列を取得します。
     *
     * @return Into 挿入の問い合わせクエリの対象となる表と列
     */
    public function getInto(): Into
    {
        return $this->into;
    }

    /**
     * 挿入の問い合わせクエリの対象となる表と列を設定します。
     *
     * @param Into $into 挿入の問い合わせクエリの対象となる表と列
     */
    public function setInto(Into $into)
    {
        $this->into = $into;
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
        return sprintf($this->getQueryFormat(), $this->getInto(), $this->getValues());
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
