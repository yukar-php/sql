<?php
namespace Yukar\Sql\Builder\Common\Functions;

/**
 * SQL の SUM 関数を表します。
 *
 * @package Yukar\Sql\Builder\Common\Functions
 * @author hiroki sugawara
 */
class Sum extends BaseFilterableFunction
{
    /**
     * Sum クラスの新しいインスタンスを初期化します。
     *
     * @param string $column 集計関数の対象となる表の列名
     * @param int    $filter 関数にかけるフィルターの種類
     */
    public function __construct(string $column, int $filter = self::FILTER_ALL)
    {
        $this->setColumn($column);
        $this->setFilter($filter);
    }
}
