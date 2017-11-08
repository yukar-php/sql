<?php
namespace Yukar\Sql\Interfaces\Builder\Common\Statements;

use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Builder\Common\Statements\Phrases\Set;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ICondition;

/**
 * 更新の問い合わせクエリを定義するインターフェイス。
 *
 * @package Yukar\Sql\Interfaces\Builder\Common\Statements
 * @author hiroki sugawara
 */
interface IUpdateQuery extends IConditionalDMLQuery
{
    /**
     * 更新の問い合わせクエリの対象となる列とその値の組み合わせリストを取得します。
     *
     * @return Set 更新の問い合わせクエリの対象となる列とその値の組み合わせリスト
     */
    public function getUpdateSets(): Set;

    /**
     * 更新の問い合わせクエリの対象となる列とその値の組み合わせリストを設定します。
     *
     * @param Set $update_sets 更新の問い合わせクエリの対象となる列とその値の組み合わせリスト
     */
    public function setUpdateSets(Set $update_sets): void;

    /**
     * 問い合わせクエリの条件式を設定します。
     *
     * @param ICondition $condition 問い合わせクエリの条件式
     *
     * @throws \InvalidArgumentException 引数 condition の要素が空の場合
     *
     * @return IUpdateQuery 条件式を設定した状態のオブジェクトのインスタンス
     */
    public function setWhere(ICondition $condition): IUpdateQuery;

    /**
     * 更新の問い合わせクエリのデータソースとなる表やサブクエリを取得します。
     *
     * @return From 更新の問い合わせクエリのデータソースとなる表やサブクエリ
     */
    public function getFrom(): ?From;

    /**
     * 更新の問い合わせクエリのデータソースとなる表やサブクエリを設定します。
     *
     * @param From $from 更新の問い合わせクエリのデータソースとなる表やサブクエリ
     *
     * @return IUpdateQuery 対象となる列のリストを設定した状態のオブジェクトのインスタンス
     */
    public function setFrom(From $from): IUpdateQuery;
}
