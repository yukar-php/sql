<?php
namespace Yukar\Sql\Builder\Statements\Dml;

use Yukar\Sql\Builder\Objects\Columns;
use Yukar\Sql\Builder\Statements\Phrases\From;
use Yukar\Sql\Builder\Statements\Phrases\GroupBy;
use Yukar\Sql\Builder\Statements\Phrases\Join;
use Yukar\Sql\Builder\Statements\Phrases\OrderBy;
use Yukar\Sql\Interfaces\Builder\Objects\IColumns;
use Yukar\Sql\Interfaces\Builder\Objects\ICondition;
use Yukar\Sql\Interfaces\Builder\Objects\ISqlQuerySource;
use Yukar\Sql\Interfaces\Builder\Statements\ISelectQuery;

/**
 * テーブルからデータを抽出する検索の問い合わせクエリの機能を提供します。
 *
 * @author hiroki sugawara
 */
class Select extends BaseConditionalDMLQuery implements ISelectQuery
{
    private $is_distinct = false;
    private $columns;
    private $join;
    private $group_by;
    private $order_by;

    /**
     * Select クラスの新しいインスタンスを初期化します。
     *
     * @param From $from        検索の問い合わせクエリの対象となる表やサブクエリ
     * @param IColumns $columns 検索の問い合わせクエリの対象となる列のリスト
     */
    public function __construct(From $from, IColumns $columns = null)
    {
        $this->setSqlQuerySource($from);
        $this->setColumns($columns ?? new Columns());
    }

    /**
     * データ操作言語の問い合わせクエリの書式を取得します。
     *
     * @return string データ操作言語の問い合わせクエリの書式
     */
    public function getQueryFormat(): string
    {
        return 'SELECT %s %s %s';
    }

    /**
     * SQLのデータ操作言語の対象となる表やサブクエリを設定します。
     *
     * @param ISqlQuerySource $sql_query_source SQLのデータ操作言語の対象となる表やサブクエリ
     */
    public function setSqlQuerySource(ISqlQuerySource $sql_query_source)
    {
        if ($sql_query_source instanceof From === false) {
            throw new \InvalidArgumentException();
        }

        parent::setSqlQuerySource($sql_query_source);
    }

    /**
     * 検索の問い合わせ結果から重複データを取り除くかどうかを取得します。
     *
     * @return bool 検索の問い合わせ結果から重複データを取り除くかどうか
     */
    public function getDistinct(): bool
    {
        return $this->is_distinct;
    }

    /**
     * 検索の問い合わせ結果から重複データを取り除くかどうかを設定します。
     *
     * @param bool $distinct 検索の問い合わせ結果から重複データを取り除くかどうか
     *
     * @return ISelectQuery 重複データの取り扱いを設定した状態のオブジェクトのインスタンス
     */
    public function setDistinct(bool $distinct): ISelectQuery
    {
        $this->is_distinct = $distinct;

        return $this;
    }

    /**
     * 検索の問い合わせクエリの対象となる列のリストを取得します。
     *
     * @return IColumns 検索の問い合わせクエリの対象となる列のリスト
     */
    public function getColumns(): IColumns
    {
        return $this->columns;
    }

    /**
     * 検索の問い合わせクエリの対象となる列のリストを設定します。
     *
     * @param IColumns $columns 検索の問い合わせクエリの対象となる列のリスト
     *
     * @return ISelectQuery 対象となる列のリストを設定した状態のオブジェクトのインスタンス
     */
    public function setColumns(IColumns $columns): ISelectQuery
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * 問い合わせクエリに結合する表の名前またはサブクエリとその結合条件を取得します。
     *
     * @return Join|null 問い合わせクエリに結合する表の名前またはサブクエリとその結合条件
     */
    public function getJoin()
    {
        return $this->join;
    }

    /**
     * 問い合わせクエリに結合する表の名前またはサブクエリとその結合条件を設定します。
     *
     * @param Join $join 問い合わせクエリに結合する表の名前またはサブクエリとその結合条件
     *
     * @return ISelectQuery 結合する表の名前またはサブクエリとその結合条件を設定した状態のオブジェクトのインスタンス
     */
    public function setJoin(Join $join): ISelectQuery
    {
        $this->join = $join;

        return $this;
    }

    /**
     * 問い合わせクエリの条件式を設定します。
     *
     * @param ICondition $condition 問い合わせクエリの条件式
     *
     * @throws \InvalidArgumentException 引数 condition の要素が空の場合
     *
     * @return ISelectQuery 条件式を設定した状態のオブジェクトのインスタンス
     */
    public function setWhere(ICondition $condition): ISelectQuery
    {
        $this->setWhereCondition($condition);

        return $this;
    }

    /**
     * 問い合わせクエリのグループ化に関する表リストや条件式を取得します。
     *
     * @return GroupBy|null 問い合わせクエリのグループ化に関する表リストや条件式
     */
    public function getGroupBy()
    {
        return $this->group_by;
    }

    /**
     * 問い合わせクエリのグループ化に関する表リストや条件式を設定します。
     *
     * @param GroupBy $group_by 問い合わせクエリのグループ化に関する表リストや条件式
     *
     * @return ISelectQuery グループ化に関する表リストや条件式を設定した状態のオブジェクトのインスタンス
     */
    public function setGroupBy(GroupBy $group_by): ISelectQuery
    {
        $this->group_by = $group_by;

        return $this;
    }

    /**
     * 問い合わせクエリの結果のカラムのソート順を取得します。
     *
     * @return OrderBy|null 問い合わせクエリの結果のカラムのソート順
     */
    public function getOrderBy()
    {
        return $this->order_by;
    }

    /**
     * 問い合わせクエリの結果のカラムのソート順を設定します。
     *
     * @param OrderBy $order_by 問い合わせクエリの結果のカラムのソート順
     *
     * @return ISelectQuery 結果のカラムのソート順を設定した状態のオブジェクトのインスタンス
     */
    public function setOrderBy(OrderBy $order_by): ISelectQuery
    {
        $this->order_by = $order_by;

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
            (($this->getDistinct() === true) ? 'DISTINCT ' : '') . $this->getColumns(),
            $this->getSqlQuerySource(),
            $this->joinQuery($this->getJoin(), $this->getWhereString(), $this->getGroupBy(), $this->getOrderBy())
        );
    }
}
