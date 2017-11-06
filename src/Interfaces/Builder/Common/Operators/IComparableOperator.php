<?php
namespace Yukar\Sql\Interfaces\Builder\Common\Operators;

/**
 * 比較可能な演算子を定義するインターフェイス。
 *
 * @package Yukar\Sql\Interfaces\Builder\Common\Operators
 * @author hiroki sugawara
 */
interface IComparableOperator extends IConditionContainable
{
    /** 式の演算子が「=（等しい）」であることを示す定数 */
    public const SIGN_EQ = 1;
    /** 式の演算子が「!=（等しくない）」であることを示す定数 */
    public const SIGN_NE = 2;
    /** 式の演算子が「>（より大きい）」であることを示す定数 */
    public const SIGN_GT = 3;
    /** 式の演算子が「>=（以上）」であることを示す定数 */
    public const SIGN_AO = 4;
    /** 式の演算子が「<（より小さい）」であることを示す定数 */
    public const SIGN_LT = 5;
    /** 式の演算子が「<=（以下）」であることを示す定数 */
    public const SIGN_OU = 6;

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
     *
     * @throws \InvalidArgumentException 引数 sign に渡した値が定義されている定数以外の数値の場合
     */
    public function setSign(int $sign);
}
