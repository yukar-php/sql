<?php
namespace Yukar\Sql\Interfaces\Builder\Common\Functions;

/**
 * フィルタ可能な関数を定義するインターフェイス。
 *
 * @package Yukar\Sql\Interfaces\Builder\Common\Functions
 * @author hiroki sugawara
 */
interface IFilterableFunction extends IAggregateFunction
{
    /** 関数のフィルタが「ALL」であることを示す定数 */
    public const FILTER_ALL = 1;
    /** 関数のフィルタが「DISTINCT」であることを示す定数 */
    public const FILTER_DISTINCT = 2;

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
    public function setFilter(int $filter): void;
}
