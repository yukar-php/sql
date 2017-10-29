<?php
namespace Yukar\Sql\Builder\Common\Functions;

use Yukar\Sql\Interfaces\Builder\Common\Functions\IFilterableFunction;

/**
 * フィルタ可能な関数の基本機能を提供する抽象クラスです。
 *
 * @package Yukar\Sql\Builder\Common\Functions
 * @author hiroki sugawara
 */
abstract class BaseFilterableFunction extends BaseAggregateFunction implements IFilterableFunction
{
    protected const FILTERS = [
        self::FILTER_ALL      => 'ALL',
        self::FILTER_DISTINCT => 'DISTINCT',
    ];

    private $filter = self::FILTER_ALL;

    /**
     * 関数にかけるフィルタを取得します。
     *
     * @return string 関数にかけるフィルタ
     */
    public function getFilter(): string
    {
        return strval(self::FILTERS[$this->filter]);
    }

    /**
     * 関数にかけるフィルターを設定します。
     *
     * @param int $filter 関数にかけるフィルターの種類
     */
    public function setFilter(int $filter): void
    {
        $this->filter = array_key_exists($filter, self::FILTERS) ? $filter : self::FILTER_ALL;
    }

    /**
     * SQLの関数や計算式を文字列として取得します。
     *
     * @return string SQLの関数や計算式
     */
    public function __toString(): string
    {
        return sprintf(
            $this->getFunctionFormat(),
            $this->getFunctionName(),
            $this->joinQuery($this->getDistinctString(), $this->getColumn())
        );
    }

    /**
     * 関数にALLフィルタが使用できないDBエンジンのために、DISTINCTフィルタのみその文字列を取得します。
     *
     * @return string 関数にDISTINCTフィルタの設定がある場合はその文字列。それ以外は空文字列。
     */
    private function getDistinctString(): string
    {
        return ($this->filter === self::FILTER_DISTINCT) ? $this->getFilter() : '';
    }

    /**
     * クエリ文字列を結合します。
     *
     * @param array ...$query_parts 結合するクエリ文字列
     *
     * @return string 結合したクエリ文字列
     */
    private function joinQuery(...$query_parts): string
    {
        return implode(' ', array_filter($query_parts, 'strlen'));
    }
}
