<?php
namespace Yukar\Sql\Interfaces\Builder\Common\Objects;

/**
 * SQLクエリの条件式を定義するインターフェイス。
 *
 * @package Yukar\Sql\Interfaces\Builder\Common\Objects
 * @author hiroki sugawara
 */
interface ICondition
{
    /**
     * 条件式の論理演算子を取得します。
     *
     * @return string 条件式の論理演算子
     */
    public function getOperation(): string;

    /**
     * 条件式の論理演算子を設定します。
     *
     * @param int $operate_type 論理演算子の種類。
     *                          論理積の場合は、Conditions::OPERATION_ANDを、論理和の場合は、Conditions::OPERATION_OR。
     */
    public function setOperation(int $operate_type): void;

    /**
     * 条件式の要素を取得します。
     *
     * @return array 条件式の要素となる配列
     */
    public function getConditions(): array;

    /**
     * 条件式の要素を追加します。
     *
     * @param string|ICondition $condition 追加する条件式の要素
     *
     * @throws \TypeError         引数 $condition が、条件式の要素とならない型の場合
     * @throws \OverflowException 条件式の要素が既に2個指定済みであった場合
     *
     * @return ICondition 条件式の要素を追加した状態の ICondition を継承するオブジェクトのインスタンス
     */
    public function addCondition($condition): ICondition;

    /**
     * 条件式の要素を設定します。
     *
     * @param string|ICondition $first  条件式の一つ目の要素
     * @param string|ICondition $second 条件式の二つ目の要素
     *
     * @throws \TypeError 引数 $first または $second が、条件式の要素とならない型の場合
     *
     * @return ICondition 条件式の要素を設定した状態の ICondition を継承するオブジェクトのインスタンス
     */
    public function setConditions($first, $second): ICondition;

    /**
     * オブジェクトを表す文字列を取得します。
     *
     * @return string オブジェクトを表す文字列
     */
    public function __toString(): string;
}
