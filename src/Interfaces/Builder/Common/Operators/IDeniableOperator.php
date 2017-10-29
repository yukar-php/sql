<?php
namespace Yukar\Sql\Interfaces\Builder\Operators;

/**
 * 否定演算子を付与することができる演算子を定義するインターフェイス。
 *
 * @author hiroki sugawara
 */
interface IDeniableOperator extends IConditionContainable
{
    /**
     * NOT演算子を付与するかどうかを取得します。
     *
     * @return bool NOT演算子を付与するかどうか
     */
    public function isNot(): bool;

    /**
     * NOT演算子を付与するかどうかを設定します。
     *
     * @param bool $is_not NOT演算子を付与するかどうか
     */
    public function setIsNot(bool $is_not);
}
