<?php
namespace Yukar\Sql\Interfaces\Builder\Common\Operators;

/**
 * 条件式に含めることのできる演算子を定義するインターフェイス。
 *
 * @author hiroki sugawara
 */
interface IConditionContainable extends IOperator
{
    /**
     * 演算子を取得します。
     *
     * @return string 演算子
     */
    public function getOperator(): string;

    /**
     * 演算子の対象となる表や列の名前を取得します。
     *
     * @return string 演算子の対象となる表や列の名前
     */
    public function getName(): string;

    /**
     * 演算子の対象となる表や列の名前を設定します。
     *
     * @param string $name 演算子の対象となる表や列の名前
     *
     * @throws \InvalidArgumentException 引数 name に渡した値が空文字列の場合
     */
    public function setName(string $name);

    /**
     * 演算子の対象となる表や列の値と比較する値を取得します。
     *
     * @return string 演算子の対象となる表や列の値と比較する値
     */
    public function getValue(): string;
}
