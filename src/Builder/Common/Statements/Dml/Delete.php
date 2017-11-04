<?php
namespace Yukar\Sql\Builder\Common\Statements\Dml;

use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ICondition;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ISqlQuerySource;

/**
 * テーブルのデータを除去する削除の問い合わせクエリの機能を提供します。
 *
 * @package Yukar\Sql\Builder\Common\Statements\Dml
 * @author hiroki sugawara
 */
class Delete extends BaseConditionalDMLQuery
{
    /**
     * Delete クラスの新しいインスタンスを初期化します。
     *
     * @param From $from 削除の問い合わせクエリの対象となる表やサブクエリ
     */
    public function __construct(From $from)
    {
        $this->setSqlQuerySource($from);
    }

    /**
     * SQLのデータ操作言語の問い合わせクエリの書式を取得します。
     *
     * @return string SQLのデータ操作言語の問い合わせクエリの書式
     */
    public function getQueryFormat(): string
    {
        return 'DELETE %s %s';
    }

    /**
     * SQLのデータ操作言語の対象となる表やサブクエリを設定します。
     *
     * @param ISqlQuerySource $sql_query_source SQLのデータ操作言語の対象となる表やサブクエリ
     */
    public function setSqlQuerySource(ISqlQuerySource $sql_query_source): void
    {
        if ($sql_query_source instanceof From === false) {
            throw new \InvalidArgumentException();
        }

        parent::setSqlQuerySource($sql_query_source);
    }

    /**
     * 問い合わせクエリの条件式を設定します。
     *
     * @param ICondition $condition 問い合わせクエリの条件式
     *
     * @throws \InvalidArgumentException 引数 condition の要素が空の場合
     *
     * @return Delete 条件式を設定した状態のオブジェクトのインスタンス
     */
    public function setWhere(ICondition $condition): Delete
    {
        $this->setWhereCondition($condition);

        return $this;
    }

    /**
     * SQLのデータ操作言語の問い合わせクエリを文字列として取得します。
     *
     * @return string SQLのデータ操作言語の問い合わせクエリ
     */
    public function __toString(): string
    {
        return $this->getFormatRightTrim($this->getSqlQuerySource(), $this->getWhereString());
    }
}
