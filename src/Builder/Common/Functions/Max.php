<?php
namespace Yukar\Sql\Builder\Common\Functions;

/**
 * SQL の MAX 関数を表します。
 *
 * @package Yukar\Sql\Builder\Common\Functions
 * @author hiroki sugawara
 */
class Max extends BaseAggregateFunction
{
    /**
     * Max クラスの新しいインスタンスを初期化します。
     *
     * @param string $column 集計関数の対象となる表の列名
     */
    public function __construct(string $column)
    {
        $this->setColumn($column);
    }
}
