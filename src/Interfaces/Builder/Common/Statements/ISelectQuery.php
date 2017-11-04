<?php
namespace Yukar\Sql\Interfaces\Builder\Common\Statements;

use Yukar\Sql\Builder\Common\Statements\Phrases\GroupBy;
use Yukar\Sql\Builder\Common\Statements\Phrases\Join;
use Yukar\Sql\Builder\Common\Statements\Phrases\OrderBy;
use Yukar\Sql\Interfaces\Builder\Common\Objects\IColumns;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ICondition;

/**
 * 検索の問い合わせクエリを定義するインターフェイス。
 *
 * @package Yukar\Sql\Interfaces\Builder\Common\Statements
 * @author hiroki sugawara
 */
interface ISelectQuery extends IConditionalDMLQuery
{
    /** 問い合わせクエリのフィルタが「ALL」であることを示す定数 */
    public const FILTER_ALL = 1;
    /** 問い合わせクエリのフィルタが「DISTINCT」であることを示す定数 */
    public const FILTER_DISTINCT = 2;

    /**
     * 検索の問い合わせ結果から重複データを取り除くフィルタを取得します。
     *
     * @return string 検索の問い合わせ結果から重複データを取り除くフィルタ
     */
    public function getFilter(): string;

    /**
     * 検索の問い合わせ結果から重複データを取り除くフィルタを設定します。
     *
     * @param int $filter_type 検索の問い合わせ結果から重複データを取り除くフィルタの種類
     *
     * @return ISelectQuery 重複データを取り除くフィルタを設定した状態のオブジェクトのインスタンス
     */
    public function setFilter(int $filter_type): ISelectQuery;

    /**
     * 検索の問い合わせクエリの対象となる列のリストを取得します。
     *
     * @return IColumns 検索の問い合わせクエリの対象となる列のリスト
     */
    public function getColumns(): IColumns;

    /**
     * 検索の問い合わせクエリの対象となる列のリストを設定します。
     *
     * @param IColumns $columns 検索の問い合わせクエリの対象となる列のリスト
     *
     * @return ISelectQuery 対象となる列のリストを設定した状態のオブジェクトのインスタンス
     */
    public function setColumns(IColumns $columns): ISelectQuery;

    /**
     * 問い合わせクエリに結合する表の名前またはサブクエリとその結合条件を取得します。
     *
     * @return Join 問い合わせクエリに結合する表の名前またはサブクエリとその結合条件
     */
    public function getJoin(): ?Join;

    /**
     * 問い合わせクエリに結合する表の名前またはサブクエリとその結合条件を設定します。
     *
     * @param Join $join 問い合わせクエリに結合する表の名前またはサブクエリとその結合条件
     *
     * @return ISelectQuery 結合する表の名前またはサブクエリとその結合条件を設定した状態のオブジェクトのインスタンス
     */
    public function setJoin(Join $join): ISelectQuery;

    /**
     * 問い合わせクエリの条件式を設定します。
     *
     * @param ICondition $condition 問い合わせクエリの条件式
     *
     * @throws \InvalidArgumentException 引数 condition の要素が空の場合
     *
     * @return ISelectQuery 条件式を設定した状態のオブジェクトのインスタンス
     */
    public function setWhere(ICondition $condition): ISelectQuery;

    /**
     * 問い合わせクエリのグループ化に関する表リストや条件式を取得します。
     *
     * @return GroupBy 問い合わせクエリのグループ化に関する表リストや条件式
     */
    public function getGroupBy(): ?GroupBy;

    /**
     * 問い合わせクエリのグループ化に関する表リストや条件式を設定します。
     *
     * @param GroupBy $group_by 問い合わせクエリのグループ化に関する表リストや条件式
     *
     * @return ISelectQuery グループ化に関する表リストや条件式を設定した状態のオブジェクトのインスタンス
     */
    public function setGroupBy(GroupBy $group_by): ISelectQuery;

    /**
     * 問い合わせクエリの結果のカラムのソート順を取得します。
     *
     * @return OrderBy 問い合わせクエリの結果のカラムのソート順
     */
    public function getOrderBy(): ?OrderBy;

    /**
     * 問い合わせクエリの結果のカラムのソート順を設定します。
     *
     * @param OrderBy $order_by 問い合わせクエリの結果のカラムのソート順
     *
     * @return ISelectQuery 結果のカラムのソート順を設定した状態のオブジェクトのインスタンス
     */
    public function setOrderBy(OrderBy $order_by): ISelectQuery;
}
