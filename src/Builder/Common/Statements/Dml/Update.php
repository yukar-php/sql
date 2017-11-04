<?php
namespace Yukar\Sql\Builder\Common\Statements\Dml;

use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Builder\Common\Statements\Phrases\Set;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ICondition;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ISqlQuerySource;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ITable;
use Yukar\Sql\Interfaces\Builder\Common\Statements\IUpdateQuery;

/**
 * テーブルのデータを書き換える更新の問い合わせクエリの機能を提供します。
 *
 * @package Yukar\Sql\Builder\Common\Statements\Dml
 * @author hiroki sugawara
 */
class Update extends BaseConditionalDMLQuery implements IUpdateQuery
{
    private $update_sets;
    private $from;

    /**
     * Update クラスの新しいインスタンスを初期化します。
     *
     * @param ITable $data_source SQLのデータ操作言語の対象となる表やサブクエリ
     * @param Set $update_sets    更新の問い合わせクエリの対象となる列とその値の組み合わせリスト
     */
    public function __construct(ITable $data_source, Set $update_sets)
    {
        $this->setSqlQuerySource($data_source);
        $this->setUpdateSets($update_sets);
    }

    /**
     * SQLのデータ操作言語の問い合わせクエリの書式を取得します。
     *
     * @return string SQLのデータ操作言語の問い合わせクエリの書式
     */
    public function getQueryFormat(): string
    {
        return 'UPDATE %s %s %s';
    }

    /**
     * SQLのデータ操作言語の対象となる表やサブクエリを設定します。
     *
     * @param ISqlQuerySource $sql_query_source SQLのデータ操作言語の対象となる表やサブクエリ
     */
    public function setSqlQuerySource(ISqlQuerySource $sql_query_source): void
    {
        if ($sql_query_source instanceof ITable === false) {
            throw new \InvalidArgumentException();
        }

        parent::setSqlQuerySource($sql_query_source);
    }

    /**
     * 更新の問い合わせクエリの対象となる列とその値の組み合わせリストを取得します。
     *
     * @return Set 更新の問い合わせクエリの対象となる列とその値の組み合わせリスト
     */
    public function getUpdateSets(): Set
    {
        return $this->update_sets;
    }

    /**
     * 更新の問い合わせクエリの対象となる列とその値の組み合わせリストを設定します。
     *
     * @param Set $update_sets 更新の問い合わせクエリの対象となる列とその値の組み合わせリスト
     */
    public function setUpdateSets(Set $update_sets): void
    {
        $this->update_sets = $update_sets;
    }

    /**
     * 問い合わせクエリの条件式を設定します。
     *
     * @param ICondition $condition 問い合わせクエリの条件式
     *
     * @throws \InvalidArgumentException 引数 condition の要素が空の場合
     *
     * @return IUpdateQuery 条件式を設定した状態のオブジェクトのインスタンス
     */
    public function setWhere(ICondition $condition): IUpdateQuery
    {
        $this->setWhereCondition($condition);

        return $this;
    }

    /**
     * 更新の問い合わせクエリのデータソースとなる表やサブクエリを取得します。
     *
     * @return From 更新の問い合わせクエリのデータソースとなる表やサブクエリ
     */
    public function getFrom(): ?From
    {
        return $this->from;
    }

    /**
     * 更新の問い合わせクエリのデータソースとなる表やサブクエリを設定します。
     *
     * @param From $from 更新の問い合わせクエリのデータソースとなる表やサブクエリ
     *
     * @return IUpdateQuery 対象となる列のリストを設定した状態のオブジェクトのインスタンス
     */
    public function setFrom(From $from): IUpdateQuery
    {
        $this->from = $from;

        return $this;
    }

    /**
     * SQLのデータ操作言語の問い合わせクエリを文字列として取得します。
     *
     * @return string SQLのデータ操作言語の問い合わせクエリ
     */
    public function __toString(): string
    {
        return $this->getFormatRightTrim(
            $this->getSqlQuerySource(),
            $this->getUpdateSets(),
            $this->joinQuery($this->getFrom(), $this->getWhereString())
        );
    }
}
