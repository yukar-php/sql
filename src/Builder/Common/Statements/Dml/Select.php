<?php
namespace Yukar\Sql\Builder\Common\Statements\Dml;

use Yukar\Sql\Builder\Common\Objects\Columns;
use Yukar\Sql\Builder\Common\Statements\Phrases\From;
use Yukar\Sql\Builder\Common\Statements\Phrases\GroupBy;
use Yukar\Sql\Builder\Common\Statements\Phrases\OrderBy;
use Yukar\Sql\Interfaces\Builder\Common\Objects\IColumns;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ICondition;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ISqlQuerySource;
use Yukar\Sql\Interfaces\Builder\Common\Statements\ISelectQuery;

/**
 * テーブルからデータを抽出する検索の問い合わせクエリの機能を提供します。
 *
 * @package Yukar\Sql\Builder\Common\Statements\Dml
 * @author hiroki sugawara
 */
class Select extends BaseConditionalDMLQuery implements ISelectQuery
{
    protected const FILTERS = [
        self::FILTER_ALL      => 'ALL',
        self::FILTER_DISTINCT => 'DISTINCT',
    ];

    private $filter_type = self::FILTER_ALL;
    private $columns;
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
     *
     * @throws \InvalidArgumentException 引数 sql_query_source に From クラスのインスタンスを渡さなかった場合
     */
    public function setSqlQuerySource(ISqlQuerySource $sql_query_source): void
    {
        if ($sql_query_source instanceof From === false) {
            throw new \InvalidArgumentException();
        }

        parent::setSqlQuerySource($sql_query_source);
    }

    /**
     * 検索の問い合わせ結果から重複データを取り除くフィルタを取得します。
     *
     * @return string 検索の問い合わせ結果から重複データを取り除くフィルタ
     */
    public function getFilter(): string
    {
        return ($this->filter_type === self::FILTER_ALL) ? '' : strval(self::FILTERS[$this->filter_type]);
    }

    /**
     * 検索の問い合わせ結果から重複データを取り除くフィルタを設定します。
     *
     * @param int $filter_type 検索の問い合わせ結果から重複データを取り除くフィルタの種類
     *
     * @return ISelectQuery 重複データを取り除くフィルタを設定した状態のオブジェクトのインスタンス
     */
    public function setFilter(int $filter_type): ISelectQuery
    {
        $this->filter_type = array_key_exists($filter_type, self::FILTERS) ? $filter_type : self::FILTER_ALL;

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
     * @return GroupBy 問い合わせクエリのグループ化に関する表リストや条件式
     */
    public function getGroupBy(): ?GroupBy
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
     * @return OrderBy 問い合わせクエリの結果のカラムのソート順
     */
    public function getOrderBy(): ?OrderBy
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
            ltrim(sprintf('%s %s', $this->getFilter(), $this->getColumns())),
            $this->getSqlQuerySource(),
            $this->joinQuery($this->getWhereString(), $this->getGroupBy(), $this->getOrderBy())
        );
    }
}
