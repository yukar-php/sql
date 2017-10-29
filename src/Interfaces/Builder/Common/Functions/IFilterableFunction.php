<?php
namespace Yukar\Sql\Interfaces\Builder\Common\Functions;

/**
 * フィルタ可能な関数を定義するインターフェイス。
 *
 * @author hiroki sugawara
 */
interface IFilterableFunction extends IAggregateFunction
{
    /** 関数のフィルタが「ALL」であることを示す定数 */
    const FILTER_ALL = 1;
    /** 関数のフィルタが「DISTINCT」であることを示す定数 */
    const FILTER_DISTINCT = 2;

    const FILTERS = [
        self::FILTER_ALL      => 'ALL',
        self::FILTER_DISTINCT => 'DISTINCT',
    ];

    /**
     * 関数にかけるフィルタを取得します。
     *
     * @return string 関数にかけるフィルタ
     */
    public function getFilter(): string;

    /**
     * 関数にかけるフィルターを設定します。
     *
     * @param int $filter 関数にかけるフィルターの種類
     */
    public function setFilter(int $filter);
}
