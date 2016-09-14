<?php
namespace Yukar\Sql\Interfaces\Builder\Operators;

/**
 * 比較可能な演算子を定義するインターフェイス。
 *
 * @author hiroki sugawara
 */
interface IComparableOperator extends IConditionContainable
{
    /**
     * 比較演算子を取得します。
     *
     * @return string 比較演算子
     */
    public function getSign(): string;

    /**
     * 比較演算子を設定します。
     *
     * @param int $sign 比較演算子
     */
    public function setSign(int $sign);
}
