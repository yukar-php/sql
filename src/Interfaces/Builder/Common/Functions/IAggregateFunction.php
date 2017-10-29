<?php
namespace Yukar\Sql\Interfaces\Builder\Common\Functions;

/**
 * 集計関数を定義するインターフェイス。
 *
 * @author hiroki sugawara
 */
interface IAggregateFunction extends IFunction
{
    /**
     * 集計関数の対象となる表の列名を取得します。
     *
     * @return string 集計関数の対象となる表の列名
     */
    public function getColumn(): string;

    /**
     * 集計関数の対象となる表の列名を設定します。
     *
     * @param string $column 集計関数の対象となる表の列名
     */
    public function setColumn(string $column);
}
