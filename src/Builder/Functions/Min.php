<?php
namespace Yukar\Sql\Builder\Functions;

/**
 * SQL の MIN 関数を表します。
 *
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

    /**
     * SQLの関数の名前を取得します。
     *
     * @return string SQLの関数の名前
     */
    public function getFunctionName(): string
    {
        return 'MIN';
    }
}
