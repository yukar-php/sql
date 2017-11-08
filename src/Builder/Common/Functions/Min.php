<?php
namespace Yukar\Sql\Builder\Common\Functions;

/**
 * SQL の MIN 関数を表します。
 *
 * @package Yukar\Sql\Builder\Common\Functions
 * @author hiroki sugawara
 */
class Min extends BaseAggregateFunction
{
    /**
     * Min クラスの新しいインスタンスを初期化します。
     *
     * @param string $column 集計関数の対象となる表の列名
     */
    public function __construct(string $column)
    {
        $this->setColumn($column);
    }
}
